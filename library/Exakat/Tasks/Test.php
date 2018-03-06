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

use Exakat\Analyzer\Analyzer;
use Exakat\Config;
use Exakat\Exceptions\NoSuchFile;
use Exakat\Exceptions\NoSuchDir;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Tasks\CleanDb;

class Test extends Tasks {
    const CONCURENCE = self::NONE;

    public function run() {
        // Check for requested file
        if (!empty($this->config->filename) && !file_exists($this->config->filename)) {
            throw new NoSuchFile($this->config->filename);
        } elseif (!empty($this->config->dirname) && !file_exists($this->config->dirname)) {
            throw new NoSuchDir($this->config->filename);
        }

        // Check for requested analyze
        $analyzerName = $this->config->program;
        if (!$this->themes->getClass($analyzerName)) {
            throw new NoSuchAnalyzer($analyzerName);
        }

        display("Cleaning DB\n");
        $clean = new CleanDb($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $clean->run();

        $load = new Load($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $load->run();
        unset($load);
        display("Project loaded\n");

        $analyze = new Analyze($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $analyze->run();
        unset($analyze);

        $results = new Results($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $results->run();
        unset($results);

        display("Analyzed project\n");
    }
}

?>