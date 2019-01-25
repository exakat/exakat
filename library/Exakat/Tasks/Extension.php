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


namespace Exakat\Tasks;

use Exakat\Config;

class Extension extends Tasks {
    const CONCURENCE = self::ANYTIME;
    
    private $extensionList = array();

    //install, list, local, uninstall, upgrade
    public function run() {
        if (!in_array($this->config->subcommand, array('install',
                                                       'uninstall',
                                                       'list',
                                                       'local',
                                                       'update',
                                                     ))) {
            $this->local();
        } else {
            $this->{$this->config->subcommand}();
        }
    }
    
    private function install() {
        if (file_exists("{$this->config->dir_root}/ext/{$this->config->extension}.phar")) {
            print "This extension already exists in the ext folder. Remove it manually, or with 'uninstall' command.\n";
            return;
        }
    
        $this->fetchExtensionList();

        if (!isset($this->extensionList[$this->config->extension])) {
            print "Couldn't find that extension on the remote server. Aborting\n";
            return;
        }
    
        $query = http_build_query(array('file' => $this->config->extension));
        $raw = file_get_contents('https://www.exakat.io/extensions/index.php?'.$query);
        
        if (hash('sha256', $raw) !== $this->extensionList[$this->config->extension]->sha256) {
            print "Error while downloading the extension : the security signatures don't match. Aborting\n";
            return;
        }
        
        if (!file_exists("{$this->config->dir_root}/ext/")) {
            mkdir("{$this->config->dir_root}/ext/", 0700);
        }
    
        $extensionPhar = "{$this->config->dir_root}/ext/{$this->config->extension}.phar";
        file_put_contents($extensionPhar, $raw);
        
        print "{$this->config->extension} installed with success!\n";
    }

    private function update() {
        if (!file_exists("{$this->config->dir_root}/ext/{$this->config->extension}.phar")) {
            print "No such extension to update.\n"."{$this->config->dir_root}/ext/{$this->config->extension}.phar";
            return;
        }

        $this->fetchExtensionList();

        if (!isset($this->extensionList[$this->config->extension])) {
            print "Couldn't find that extension on the remote server. Aborting\n";
            return;
        }

        $ini = parse_ini_file("phar://{$this->config->dir_root}/ext/{$this->config->extension}.phar/config.ini");
        if ($this->extensionList[$this->config->extension]->build < $ini['build']) {
            print "The current extension is newer than the remote one. Remove with 'uninstall' first. Keeping previous version and aborting\n";
            return;
        } elseif ($this->extensionList[$this->config->extension]->build > $ini['build']) {
            print "The current extension is the same as the remote one. Remove with 'uninstall' first. Keeping previous version and aborting\n";
            return;
        }

        $query = http_build_query(array('file' => $this->config->extension));
        $raw = file_get_contents('https://www.exakat.io/extensions/index.php?'.$query);
        
        if (hash('sha256', $raw) !== $this->extensionList[$this->config->extension]->sha256) {
            print "Error while downloading the extension : the security signatures don't match. Keeping previous version. Aborting\n";
            return;
        }
        
        if (!file_exists("{$this->config->dir_root}/ext/")) {
            mkdir("{$this->config->dir_root}/ext/", 0700);
        }

        $extensionPhar = "{$this->config->dir_root}/ext/{$this->config->extension}.phar";
        file_put_contents($extensionPhar, $raw);

        print "{$this->config->extension} upgraded to ".$this->extensionList[$this->config->extension]->version." with success!\n";
    }
    
    private function uninstall() {
        if (!file_exists("{$this->config->dir_root}/ext/{$this->config->extension}.phar")) {
            print "No such extension to remove.\n";
            return;
        }
    
        print "Uninstalling the extension from exakat\n";
        unlink("{$this->config->dir_root}/ext/{$this->config->extension}.phar");
        print "Done\n";
    }
    
    private function list() {
        $json = @file_get_contents('https://www.exakat.io/extensions/index.json');
        if (empty($json)) {
            print "Coudln't reach the remote server.\n";
            return;
        }
    
        $list = json_decode($json);
        if (empty($list)) {
            print "Coudln't read the remote list.\n";
            return;
        }
        
        $names = array_column($list, 'name');
        print '+ '.implode("\n+ ", $names).PHP_EOL;
    }
    
    private function local() {
        $list = $this->config->ext->getPharList();
        sort($list);
    
        print PHP_EOL;
        printf("+ %-20s %8s %5s\n", 'Extension', 'Version', 'Build');
        print str_repeat('-', 40).PHP_EOL;
        foreach($list as $l) {
            // drop the .phar
            if (file_exists("phar://{$this->config->dir_root}/ext/$l/config.ini")) {
                $ini = parse_ini_file("phar://{$this->config->dir_root}/ext/$l/config.ini");
            } else {
                $ini = array('version' => '',
                             'build'   => '',
                             );
            }
            printf("+ %-20s %8s %5s\n", substr($l, 0, -5), $ini['version'], '('.$ini['build'].')');
        }
        
        print PHP_EOL.'Total : '.count($list).' extensions'.PHP_EOL;
    }
    
    private function fetchExtensionList() {
        $json = @file_get_contents('https://www.exakat.io/extensions/index.json');
        if (empty($json)) {
            print "Coudln't reach the remote server.\n";
            return;
        }
    
        $list = json_decode($json);
        if (empty($list)) {
            print "Coudln't read the remote list.\n";
            return;
        }
        
        foreach($list as $e) {
            $this->extensionList[$e->name] = $e;
        }
    }
}

?>
