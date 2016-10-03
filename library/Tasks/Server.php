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

class Server extends Tasks {
    private $config = null;
    
    public function run(\Exakat\Config $config) {
        if ($config->stop === true) {
            $display = @file_get_contents('http://localhost:7447/stop/');
            display('Shut down server ('.$display.')');
            die();
        }
        
        if (file_exists($config->dir_root.'/projects/index.php')) {
            display('A server is already running. Aborting.');
            die();
        }

        display('Copy router server');
        $php = file_get_contents($config->dir_root.'/server/index.php');
        $php = str_replace('__PHP__', $config->php, $php);
        $php = str_replace('__EXAKAT__', $config->executable, $php);
        file_put_contents($config->projects_root.'/projects/index.php', $php);

        display('Start server');
        exec($config->php . ' -S 0.0.0.0:7447 -t '.$config->projects_root.'/projects/ '.$config->projects_root.'/projects/index.php > /dev/null 2 > /dev/null &');
    }
}

?>
