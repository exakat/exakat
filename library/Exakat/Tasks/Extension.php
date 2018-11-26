<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

    //install, list, local, uninstall, upgrade
    public function run() {
        switch($this->config->subcommand) {
            case 'install' : 
                if (file_exists("{$this->config->dir_root}/ext/{$this->config->extension}.phar")) {
                    print "This extension already exists in the ext folder. Remove it manually, or with 'uninstall' command.\n";
                    return;
                }

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
                
                $ext = 'none';
                foreach($list as $e) {
                    if ($e->name === $this->config->extension) {
                        $ext = $e;
                        break;
                    }
                }
                
                if ($ext === 'none') {
                    print "Couldn't find that extension on the remote server. Aborting\n";
                    return;
                }

                $query = http_build_query(array('file' => $this->config->extension));
                $raw = file_get_contents('https://www.exakat.io/extensions/index.php?'.$query);
                
                if (hash('sha256', $raw) !== $ext->sha256) {
                    print "Error while downloading the extension : the security signatures don't match. Aborting\n";
                    return;
                }
                
                
                if (!file_exists("{$this->config->dir_root}/ext/")) {
                    mkdir("{$this->config->dir_root}/ext/", 0700);
                }

                file_put_contents("{$this->config->dir_root}/ext/{$this->config->extension}.phar", $raw);
                print "{$this->config->extension} installed with success!\n";
                break 1;

            case 'uninstall' : 
                if (!file_exists("{$this->config->dir_root}/ext/{$this->config->extension}.phar")) {
                    print "No such extension to remove.\n";
                    return;
                }

                print "Uninstalling the extension from exakat\n";
                sleep(2);
                unlink("{$this->config->dir_root}/ext/{$this->config->extension}.phar");
                print "Done\n";

                break 1;

            case 'list' : 
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
                print '+ '.implode(PHP_EOL, $names).PHP_EOL;
                break 1;

            case 'local' : 
                $list = $this->config->ext->getJarList();
                sort($list);

                print PHP_EOL;
                foreach($list as $l) {
                    // drop the .phar
                    print '+ '.substr($l, 0, -5).PHP_EOL;
                }
                
                print 'Total : '.count($list).' extensions'.PHP_EOL;
                break 1;
            
            default : 
                return;
        }
    }
}

?>
