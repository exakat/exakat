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

class RemoteConfig extends Config {
    private $remoteJsonFile = 'config/remotes.json';

    public function __construct(string $projects_root) {
        $this->remoteJsonFile = $projects_root . '/config/remotes.json';
    }

    public function loadConfig(project $project) : ?string {
        if (!file_exists($this->remoteJsonFile)) {
            return self::NOT_LOADED;
        }

        $json = file_get_contents($this->remoteJsonFile);
        if (empty($json)) {
            return self::NOT_LOADED;
        }

        $remotes = json_decode($json);
        if (empty($remotes)) {
            return self::NOT_LOADED;
        }

        foreach($remotes as $remote) {
            $this->config[$remote->name] = $remote->URI;
        }

        return 'config/remotes.json';
    }
}

?>