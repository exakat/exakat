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

class Queue extends Tasks {
    private $pipefile = '/tmp/onepageQueue';
    
    public function run(\Config $config) {
        if ($config->stop === true) {
            display('Stopping queue');
            $queuePipe = fopen($this->pipefile, 'w');
            fwrite($queuePipe, "quit\n");
            fclose($queuePipe);

            die('Queue stopped');
        }
        if ($config->project != 'default') {
            if (file_exists($config->projects_root.'/projects/'.$config->project.'/report/')) {
                display('Cleaning the project first');
                $clean = new Clean();
                $clean->run($config);
            }

            display('Adding project '.$config->project.' to the queue');
            $queuePipe = fopen($this->pipefile, 'w');
            if ($queuePipe == false) {
                throw new \RuntimeException('Couldn\'t open file "'.$this->pipefile.'" for queueing. Aborting'); 
            }
            fwrite($queuePipe, $config->project."\n");
            fclose($queuePipe);
        } elseif (!empty($config->filename)) {
            if (!file_exists($config->projects_root.'/in/'.$config->filename.'.php')) {
                die('No such file "'.$config->filename.'" in /in/ folder');
            }

            if (file_exists($config->projects_root.'/out/'.$config->filename.'.json')) {
                die('Report already exists for "'.$config->filename.'" in /out/ folder');
            }

            display('Adding file '.$config->project.' to the queue');
            $queuePipe = fopen($this->pipefile, 'w');
            if ($queuePipe == false) {
                throw new \RuntimeException('Couldn\'t open file "'.$this->pipefile.'" for queueing. Aborting'); 
            }
            fwrite($queuePipe, $config->filename."\n");
            fclose($queuePipe);
        }

        display('Done');
    }
}

?>
