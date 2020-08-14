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

use Exakat\Configsource\ProjectConfig;
use Exakat\Configsource\DotExakatYamlConfig;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Config as Configuration;

class Config extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run(): void {
        $project = $this->config->project;

        // May be in-code!!
        if ($this->config->inside_code === Configuration::INSIDE_CODE) {
            $projectConfig = new DotExakatYamlConfig();
            $projectConfig->loadConfig($project);
        } elseif ($this->config->project === null) {
            $projectConfig = new ProjectConfig($this->config->projects_root);
        } else {
            if (!file_exists("{$this->config->projects_root}/projects/$project")) {
                throw new NoSuchProject($this->config->project);
            }

            $projectConfig = new ProjectConfig($this->config->projects_root);
            $projectConfig->loadConfig($project);
        }

        print $projectConfig->getConfig($this->config->dir_root);
    }
}
?>
