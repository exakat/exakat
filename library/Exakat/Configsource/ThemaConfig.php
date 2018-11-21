<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class ThemaConfig extends Config {
    private $remoteIniFile = 'config/themes.ini';
    
    public function __construct($projects_root) {
        $this->remoteIniFile = $projects_root.'/config/themes.ini';
    }

    public function loadConfig($project) {
        if (!file_exists($this->remoteIniFile)) {
            return self::NOT_LOADED;
        }

        $ini = parse_ini_file($this->remoteIniFile, true);
        if (empty($ini)) {
            return self::NOT_LOADED;
        }

        foreach($ini as $name => $values) {
            // Check for actual existence and drop unknown
            $this->config[$name] = array_unique($values['analyzer']);
        }

        return 'config/themes.ini';
    }
}

?>