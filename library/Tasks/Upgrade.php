<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tasks;

class Upgrade extends Tasks {
    public function run(\Config $config) {
        $options = array(
            'http'=>array(
                'method' => 'GET',
                'header' => "User-Agent: exakat-" .\Exakat::VERSION
            )
        );

        $context = stream_context_create($options);
        $html = file_get_contents('http://dist.exakat.io/versions/index.php', true, $context);

        if (empty($html)) {
            die( "Unable to check last version. Try again later.\n");
        }
        
        preg_match('/Download exakat version (\d+\.\d+\.\d+) \(Latest\)/s', $html, $latest);
        $latest = $latest[1];
        
        if (version_compare(\Exakat::VERSION, $latest) < 0) {
            print "A new version of exakat is available : current : ".\Exakat::VERSION.", latest: ".$latest.")\n";
            if ($config->update === true) {
                if (!$config->isPhar) {
                    die( "This command can only upgrade exakat as a PHAR archive.\n  If you haved installed exakat from source, do a git pull\nAborting upgrade\n");
                }

                display( "  Updating to latest version.\n");
                preg_match('#<pre id="sha256">(.*?)</pre>#', $html, $r);

                $phar = file_get_contents('http://dist.exakat.io/versions/index.php?file=latest');
                $sha256 = $r[1];
                
                if (hash('sha256', $phar) !== $sha256) {
                    die( "Error while checking exakat.phar's checksum. Aborting update. Please, try again\n");
                }

                if (file_exists($config->projects_root.'/exakat.latest.phar')) {
                    // Removing previous temporary file. 
                    unlink($config->projects_root.'/exakat.latest.phar');
                }
                
                file_put_contents($config->projects_root.'/.exakat.latest.phar', $phar);
                display('Setting up exakat.phar');
                unlink($config->projects_root.'/exakat.phar');
                if (file_exists($config->projects_root.'/exakat.phar')) {
                    die("Can't remove the current exakat binary. Please, remove it manually, and use the exakat.latest.phar instead\nAborting upgrade\n");
                }
                rename($config->projects_root.'/exakat.latest.phar', $config->projects_root.'exakat.phar');
                die("Upgraded to latest exakat version ($latest)");
            } else {
                die( "  You may run this command with -u option to upgrade to the latest exakat version.\n");
            }
        } elseif (version_compare(\Exakat::VERSION, $r[1]) === 0) {
            die( "This is the latest version (".\Exakat::VERSION.")\n");
        } else {
            die("This version is ahead of the latest publication (Current : ".\Exakat::VERSION.", Latest: $r[1])\n");
        }
    }
}

?>
