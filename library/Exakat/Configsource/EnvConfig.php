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

namespace Exakat\Configsource;

use Exakat\Project as Project;

class EnvConfig extends Config {
    protected $config  = array();

    public function loadConfig(Project $project) : ?string {
        if (!empty($e = getenv('EXAKAT_IGNORE_RULES'))) {
            $this->config['ignore_rules'] = str2array($e);
        }

        if (!empty($e = getenv('EXAKAT_IGNORE_DIRS'))) {
            $this->config['ignore_dirs'] = str2array($e);
        }

        if (!empty($e = getenv('EXAKAT_INCLUDE_DIRS'))) {
            $this->config['include_dirs'] = str2array($e);
        }

        return 'environnment';
    }
}

?>