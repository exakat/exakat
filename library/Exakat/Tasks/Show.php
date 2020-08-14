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

use Exakat\Exceptions\InvalidProjectName;
use Exakat\Exceptions\ProjectNeeded;

class Show extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run(): void {
        $project = $this->config->project;

        if (!$project->validate()) {
            throw new InvalidProjectName($project->getError());
        }

        if ($this->config->project === 'default') {
            throw new ProjectNeeded();
        }

        print "Create project '$project' with : 
    {$this->config->php} {$this->config->executable} init -p $project -R {$this->config->project_url} -{$this->config->project_vcs} -v\n";
    }
}

?>
