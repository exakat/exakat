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

namespace Exakat\Configsource;

class RulesetConfig extends Config {
    private $remoteIniFile = false;
    
    public function __construct($exakat_root) {
        $this->remoteIniFile = "{$exakat_root}/config/rulesets.ini";

        if (!file_exists($this->remoteIniFile)) {

            if (file_exists("{$exakat_root}/config/themes.ini")) {
                display("Warning : themes.ini is obsolete, and will be replaced by rulesets.ini. Please, rename it.\n");

                $this->remoteIniFile = "{$exakat_root}/config/themes.ini";
            } else {
                $this->remoteIniFile = false;
            }
        }
    }

    public function loadConfig($project) {
        if (empty($this->remoteIniFile)) {
            return self::NOT_LOADED;
        }

        $ini = parse_ini_file($this->remoteIniFile, true);
        if (empty($ini)) {
            return self::NOT_LOADED;
        }

        foreach($ini as $name => $values) {
            if (!isset($values['analyzer'])) {
                continue;
            }

            if (!is_array($values['analyzer'])) {
                continue;
            }
            
            $list = array_filter(array_unique($values['analyzer']), 'filter_analyzer');

            if (empty($list)) {
                continue;
            }

            // Check for actual existence and drop unknown
            $this->config[$name] = $list;
        }

        return 'config/rulesets.ini';
    }
}

?>