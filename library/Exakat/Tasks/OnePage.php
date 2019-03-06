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
use Exakat\Datastore;
use Exakat\Exakat;
use Exakat\Exceptions\NoSuchFile;

class OnePage extends Tasks {
    const CONCURENCE = self::NONE;

    private $project_dir = '.';

    const TOTAL_STEPS = 7;

    public function run() {
        $begin = microtime(true);
        $this->project_dir = $this->config->projects_root.'/projects/onepage/';
        
        if (!file_exists($this->project_dir.'/code/')) {
            $this->project_dir.'/code/';
        }
        if (!file_exists($this->project_dir.'/reports/')) {
            $this->project_dir.'/reports/';
        }

        // todo : check that there is indeed this project or create it.
        if (!file_exists($this->config->filename)) {
            throw new NoSuchFile($this->config->filename);
        }

        $this->cleanLogForProject('onepage');

        display("Cleaning project\n");
        $clean = new Clean($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $clean->run();
        $this->datastore = new Datastore($this->config);
        
        $audit_start = time();
        $this->datastore->addRow('hash', array('audit_start'    => $audit_start,
                                               'exakat_version' => Exakat::VERSION,
                                               'exakat_build'   => Exakat::BUILD,
                                               ));

        display('Cleaning DB');
        $task = new CleanDb($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $task->run();

        display('Running project "onepage"');

        $task = new Load($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $task->run();

        display('Project loaded');
        $this->logTime('Loading');

        $task = new Analyze($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $task->run();

        rename($this->config->projects_root.'/projects/onepage/log/analyze.log',
               $this->config->projects_root.'/projects/onepage/log/analyze.onepage.log');

        display('Project analyzed');
        $this->logTime('Analyze');

        $task = new Dump($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $task->run();
        display('Project dumped');

        $audit_end = time();
        $this->datastore->addRow('hash', array('audit_end'    => $audit_end,
                                               'audit_length' => $audit_end - $audit_start));

        $task = new Report($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $task->run();
        display('Project reported');
        $this->logTime('Report');

        $this->logTime('Final');
        display('End 2');
        $end = microtime(true);
    }

    private function logTime($step) {
        static $log, $begin, $end, $start;

        if ($log === null) {
            $log = fopen($this->project_dir.'/log/project.timing.csv', 'w+');
        }

        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }

        fwrite($log, $step."\t".($end - $begin)."\t".($end - $start)."\n");
        $begin = $end;
    }
}

?>
