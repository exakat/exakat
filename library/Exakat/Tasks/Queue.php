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


namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Tasks\Clean;
use Exakat\Tasks\Jobqueue;
use Exakat\Exceptions\NoJobqueueStarted;

class Queue extends Tasks {
    const CONCURENCE = self::ANYTIME;
    
    private $pipefile = Jobqueue::PATH;
    
    public function run(Config $config) {
        if (!file_exists($this->pipefile)) {
            throw new NoJobqueueStarted();
        }

        if ($config->stop === true) {
            display('Stopping queue');
            $queuePipe = fopen($this->pipefile, 'w');
            fwrite($queuePipe, "quit\n");
            fclose($queuePipe);

            return;
        }

        if ($config->ping === true) {
            display('Ping queue');
            $queuePipe = fopen($this->pipefile, 'w');
            fwrite($queuePipe, "ping\n");
            fclose($queuePipe);

            return;
        }

        if ($config->project != 'default') {
            if (file_exists($config->projects_root.'/projects/'.$config->project.'/report/')) {
                display('Cleaning the project first');
                $clean = new Clean($this->gremlin);
                $clean->run($config);
            }

            display('Adding project '.$config->project.' to the queue');
            $queuePipe = fopen($this->pipefile, 'w');
            fwrite($queuePipe, $config->project."\n");
            fclose($queuePipe);
        } elseif (!empty($config->filename)) {
            if (!file_exists($config->projects_root.'/in/'.$config->filename.'.php')) {
                throw new \Exakat\Exceptions\NoSuchFile('No such file "'.$config->filename.'" in /in/ folder');
            }

            if (file_exists($config->projects_root.'/out/'.$config->filename.'.json')) {
                throw new \Exakat\Exceptions\ReportAlreadyDone($config->filename);
            }

            display('Adding file '.$config->project.' to the queue');

            $queuePipe = fopen($this->pipefile, 'w');
            fwrite($queuePipe, $config->filename."\n");
            fclose($queuePipe);
        }

        display('Done');
    }
}

?>
