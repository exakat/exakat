<?php declare(strict_types = 1);
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

namespace Exakat\Autoload;

use Phar;

class AutoloadDev implements Autoloader {
    const LOAD_ALL = null;

    private $path = '';

    public function __construct($path) {
        if (class_exists('\\Phar') && phar::running()) {
            // No autoloadDev with phar
            // Ignoring it all
            return;
        }

        $this->path = $path;
    }

    public function autoload($name): void {
        if (empty($this->path)) {
            return;
        }

        $fileName = str_replace(array('Exakat\\', '\\'), array('', DIRECTORY_SEPARATOR), $name) . '.php';

        if (file_exists("{$this->path}/$fileName")) {
            include "{$this->path}/$fileName";
        }
    }

    public function registerAutoload() {
        spl_autoload_register(array($this, 'autoload'));
    }

    public function getAllAnalyzers() {
        $fullPath = "{$this->path}/Analyzer/analyzers.ini";

        if (!file_exists($fullPath)) {
            return array();
        }

        $ini = parse_ini_file($fullPath);

        return $ini === false ? array() : $ini;
    }

    public function loadIni($name, $libel = self::LOAD_ALL) {
        $fullPath = "{$this->path}/data/$name";

        if (!file_exists($fullPath)) {
            return array();
        }

        $ini = parse_ini_file($fullPath, \INI_PROCESS_SECTIONS);
        if (empty($ini)) {
            return array();
        }

        if ($libel === self::LOAD_ALL) {
            $return = $ini;
        } else {
            $return = $ini[$libel];
        }

        return array_merge($return);
    }

    public function loadJson($name, $libel = self::LOAD_ALL) {
        $fullPath = "{$this->path}/data/$name";

        if (!file_exists($fullPath)) {
            return array();
        }

        $json = file_get_contents($fullPath);
        if (empty($json)) {
            return array();
        }

        $return = json_decode($json, true);
        if (empty($return)) {
            return array();
        }

        return $return;
    }

    public function loadData($path) {
        $fullPath = "{$this->path}/$path";

        if (file_exists($fullPath)) {
            return file_get_contents($fullPath);
        } else {
            return null;
        }
    }

}

?>