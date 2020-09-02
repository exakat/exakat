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

class RulesetConfig extends Config {
    private $remoteIniFile = false;

    public function __construct(string $exakat_root) {
        // Normal case : rulesets.ini
        $this->remoteIniFile = "{$exakat_root}/config/rulesets.ini";
        if (file_exists($this->remoteIniFile)) {
            return;
        }

        // Old case : rulesets.ini
        $this->remoteIniFile = "{$exakat_root}/config/themes.ini";
        if (file_exists($this->remoteIniFile)) {
            display("Warning : config/themes.ini is obsolete, and will be replaced by config/rulesets.ini. Please, rename it.\n");

            return;
        }

        $this->remoteIniFile = false;
    }

    public function loadConfig(Project $project) : ?string {
        if (empty($this->remoteIniFile)) {
            return self::NOT_LOADED;
        }

        $ini = parse_ini_file($this->remoteIniFile, \INI_PROCESS_SECTIONS);
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

        $this->config = self::cleanRulesets($this->config);

        return 'config/rulesets.ini';
    }

    public static function cleanRulesets(array $rulesets) {
        // hash=>array
        $rulesets = array_map('array_values', $rulesets);

        $rulesets = array_map(function (array $rules): array {
            return preg_grep('#^[^/]+/[^/]+$#', $rules);
        }, $rulesets);


        $rulesets = array_filter($rulesets);
        $rulesets = array_map('array_filter', $rulesets);

        $rulesets = array_map('array_unique', $rulesets);

        return $rulesets;
    }
}

?>