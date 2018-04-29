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

use Exakat\Configsource\ProjectConfig;
use Exakat\Datastore;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\HelperException;
use Exakat\Project;

class Config extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $project = new Project($this->config->project);

        if ($project == 'default') {
            throw new ProjectNeeded();
        }
        
        if (empty($this->config->configuration)) {
            return;
        }

        $projectConfig = new ProjectConfig($this->config->projects_root);
        $projectConfig->loadConfig($project);
        print_r($this->config->configuration);
        foreach($this->config->configuration as $key => $value) {
            if (in_array($key, array('ignore_dirs', 'include_dirs', 'file_extensions'))) {
                $projectConfig->setConfig($key,     explode(',', $value));
            } else {
                $projectConfig->setConfig($key,     $value);
            }
        }
        $projectConfig->writeConfig();
    }
}
?>
