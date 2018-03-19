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

namespace Exakat\Configsource;

class CodacyConfig extends Config {
    public function __construct($projects_root) {
        $this->projects_root = $projects_root.'/projects/';
    }

    public function loadConfig($project) {
        $this->defaultCodacyConfig();
        
        $pathToJson = "{$this->projects_root}{$project}/code/.codacy.json";
        if (!file_exists($pathToJson)) {
            return self::NOT_LOADED;
        }

        $json = file_get_contents($pathToJson);
        if (empty($json)) {
            return self::NOT_LOADED;
        }

        $config = json_decode($json);
        if (empty($config)) {
            return self::NOT_LOADED;
        }

        if (isset($config->tools)) {
            $this->config['codacy_files'] = $config->files;
        }

        if (isset($config->tools)) {
            foreach($config->tools as $tool) {
                if ($tool->name != 'exakat') { continue; }
                $this->config['codacy_analyzers'] = $tool->patterns;
            }
        }

        return '.codacy.json';
        
        // Todo : check that patterns are valid
        // Todo : check that files are valid
        // Todo : check that files are PHP
    }
    
    private function defaultCodacyConfig() {
        $this->config['codacy_files']     = 'all';
        $this->config['codacy_analyzers'] = 'all';
    }
}

?>