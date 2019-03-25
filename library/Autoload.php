<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/

use Exakat\Config;

include 'helpers.php';

register_shutdown_function('shutdown');

class Autoload {
    public static function autoload_library($name) {
        $file = __DIR__.'/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';

        if (file_exists($file)) {
            include $file;
        }
    }

    public static function autoload_test($name) {
        $path = dirname(__DIR__);

        $file = "$path/tests/analyzer/".str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';

        if (file_exists($file)) {
            include $file;
        }
    }

    public static function autoload_phpunit($name) {
        $fileName = preg_replace('/^([^_]+?)_(.*)$/', '$1'.DIRECTORY_SEPARATOR.'$2', $name);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $fileName);
        $file = "{$fileName}.php";

        if (file_exists($file)) {
            include $file;
        }
    }
}

class AutoloadExt {
    const LOAD_ALL = null;
    
    private $pharList = array();
    
    public function __construct($path) {
        if (!extension_loaded('phar')) {
            // Ignoring it all
            return;
        }
        $list = glob("$path/*.phar");
        
        foreach($list as $phar) {
            $this->pharList[basename($phar, '.phar')] = $phar;
        }
        
        // Add a list of check on the phars
        // Could we autoload everything ? 
    }

    public function autoload($name) {
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $name);
        $file = "{$fileName}.php";

        foreach($this->pharList as $phar) {
            $fullPath = "phar://$phar/$file";
            if (file_exists($fullPath)) {
                include $fullPath;
                return;
            }
        }
    }

    public function registerAutoload() {
        spl_autoload_register(array($this, 'autoload'));
    }

    public function getPharList() {
        return array_map('basename', $this->pharList);
    }

    public function getThemes() {
        $return = array();

        foreach($this->pharList as $name => $phar) {
            $fullPath = "phar://$phar/Exakat/Analyzer/analyzers.ini";
            
            if (!file_exists($fullPath)) {
                $return[] = array();
                continue; 
            }
            $ini = parse_ini_file($fullPath);
            unset($ini['All']); // And other pre-defined themes ? 
            
            $return[$name] = array_keys($ini);
        }

        return $return;
    }

    public function getAnalyzers(string $theme = 'All') {
        $return = array();

        foreach($this->pharList as $name => $phar) {
            $fullPath = "phar://$phar/Exakat/Analyzer/analyzers.ini";
            
            if (!file_exists($fullPath)) {
                $return[] = array();
                continue; 
            }
            $ini = parse_ini_file($fullPath);
            
            $return[$name] = $ini[$theme] ?? array();
        }

        return $return;
    }

    public function getAllAnalyzers() {
        $return = array();

        foreach($this->pharList as $name => $phar) {
            $fullPath = "phar://$phar/Exakat/Analyzer/analyzers.ini";
            
            if (!file_exists($fullPath)) {
                display("Missing analyzers.ini in $name\n");
                $return[] = array();
                continue; 
            }
            $ini = parse_ini_file($fullPath);
            
            $return[$name] = $ini;
        }

        return $return;
    }

    public function loadIni($name, $libel = self::LOAD_ALL) {
        $return = array();

        foreach($this->pharList as $phar) {
            $fullPath = "phar://$phar/data/$name";

            if (!file_exists($fullPath)) {
                continue;
            }
            
            $ini = parse_ini_file($fullPath, INI_PROCESS_SECTIONS);
            if (empty($ini)) {
                continue;
            }
            
            if ($libel === self::LOAD_ALL) {
                $return[] = $ini;
            } else {
                $return[] = $ini[$libel];
            }
        }
        
        if (empty($return)) {
            return array();
        }

        return array_merge(...$return);
    }

    public function loadData($path) {
        $return = array();
        foreach($this->pharList as $phar) {
            $fullPath = "phar://$phar/$path";

            if (file_exists($fullPath)) {
                $return[] = file_get_contents($fullPath);
            }
        }
        
        return implode('', $return);
    }

    public function fileExists($path) {
        foreach($this->pharList as $phar) {
            $fullPath = "phar://$phar/$path";

            if (file_exists($fullPath)) {
                return true;
            }
        }
        
        return false;
    }

    public function copyFile($path, $to) {
        foreach($this->pharList as $phar) {
            $fullPath = "phar://$phar/$path";

            if (file_exists($fullPath)) {
                copy($fullPath, $to);
            }
        }
        
        return null;
    }
}

spl_autoload_register('Autoload::autoload_library');
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    include __DIR__.'/../vendor/autoload.php';
} elseif (file_exists(__DIR__.'/../../../../vendor/autoload.php')) {
    include __DIR__.'/../../../../vendor/autoload.php';
}


?>