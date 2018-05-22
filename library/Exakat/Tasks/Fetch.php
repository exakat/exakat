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
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoDump;
use Exakat\Exceptions\ProjectNeeded;

class Fetch extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $project = $this->config->project;
        if ($project === 'default') {
            throw new ProjectNeeded();
        }

        $json = @file_get_contents($this->config->projects_root.'projects/.exakat/Project.json');
        $json = json_decode($json);
        if (isset($json->project) && $project === $json->project) {
            // Too early
            throw new NoDump($project);
        }

        $projectPath = "{$this->config->projects_root}/projects/$project";
        if (!file_exists($projectPath)) {
            throw new NoSuchProject($project);
        }

        if (!file_exists($projectPath)) {
            throw new NoSuchProject($project);
        }

        if (!file_exists("$projectPath/dump.sqlite")) {
            throw new NoSuchProject($project);
        }
        
        // transmits the dump sqlite database
        readfile("$projectPath/dump.sqlite");
    }
}

?>
