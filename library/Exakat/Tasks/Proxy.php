<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Proxy extends Tasks {
    const CONCURENCE = self::ANYTIME;
    const PORT =  7448;

    public function run() {
        if ($this->config->stop    === true ||
            $this->config->restart === true
            ) {
            $display = @file_get_contents('http://localhost:'.self::PORT.'?json=["stop"]');
            if (empty($display)) {
                $display = 'No proxy found';
            }
            display('Shut proxy server ('.$display.')');
            
            if ($this->config->stop === true) {
                return;
            }
        }

        if (file_exists($this->config->dir_root.'/projects/proxy.php')) {
            display('A server is already installed. Aborting.');
            return;
        }
        
        $slaves = $this->config->remotes;
        unset($slaves['proxy']); // remove self
        unset($slaves['ici']);   // remove queue, but why ?

        display('Copy router server');
        $php = file_get_contents($this->config->dir_root.'/server/proxy.php');
        $php = str_replace('__PHP__', $this->config->php, $php);
        $php = str_replace('__EXAKAT__', $this->config->executable, $php);
        $php = str_replace('__SLAVES__', var_export($slaves, true), $php);
        file_put_contents($this->config->projects_root.'/projects/proxy.php', $php);

        if (!file_exists($this->config->projects_root.'/projects/server.log')) {
            file_put_contents($this->config->projects_root.'/projects/server.log', date('r')."\tCreated file\n");
        }
        
        display('Start server');
//        exec($this->config->php.' -S 0.0.0.0:'.self::PORT.' -t '.$this->config->projects_root.'/projects/ '.$this->config->projects_root.'/projects/proxy.php > /dev/null 2 > /dev/null &');
        exec($this->config->php.' -S 0.0.0.0:'.self::PORT.' -t '.$this->config->projects_root.'/projects/ '.$this->config->projects_root.'/projects/proxy.php > /dev/null 2>&1 > /dev/null & ');
        display('Started server');
    }
}

?>
