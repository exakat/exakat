<?php declare(strict_types = 1);
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

use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoDump;
use Exakat\Exceptions\ProjectNeeded;

class Fetch extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run(): void {
        $project = $this->config->project;
        if ($project === 'default') {
            throw new ProjectNeeded();
        }

        $json = @file_get_contents("{$this->config->tmp_dir}/Project.json");
        $json = json_decode($json);
        if (isset($json->project) && $project === $json->project) {
            // Too early
            throw new NoDump($project);
        }

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($project);
        }

        if (!file_exists($this->config->dump)) {
            throw new NoDump($project);
        }

        // transmits the dump sqlite database
        readfile($this->config->dump);
    }
}

?>
