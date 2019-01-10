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

class CodacyConfig extends Config {
    public function __construct($projects_root) {
        $this->projects_root = "{$projects_root}/projects/";
    }

    public function loadConfig($project) {
        $this->defaultCodacyConfig();
        
        $pathToJson = "{$this->projects_root}{$project}/code/.codacy.json";
        if (!file_exists($pathToJson)) {
            return self::NOT_LOADED;
        }

        if (is_dir($pathToJson)) {
            $this->config['codacy_error'] = '.codacy.json is a directory';
            return self::NOT_LOADED;
        }

        $json = file_get_contents($pathToJson);
        if (empty($json)) {
            $this->config['codacy_error'] = '.codacy.json is empty';
            return self::NOT_LOADED;
        }

        $config = json_decode($json);
        if (empty($config)) {
            $this->config['codacy_error'] = '.codacy.json couldn\'t be decoded';
            return self::NOT_LOADED;
        }

        if (empty($config->files)) {
            $this->config['codacy_error'] = '.codacy.json lists no files';
            return self::NOT_LOADED;
        }

        if (isset($config->tools)) {
            $this->config['codacy_files'] = $config->files;
        }

        if (isset($config->tools)) {
            foreach($config->tools as $tool) {
                if ($tool->name !== 'exakat') { continue; }

                $this->config['codacy_analyzers'] = array_column($tool->patterns, 'patternId');

                if (empty($this->config['codacy_analyzers'])) {
                    $this->config['codacy_error'] = '.codacy.json lists no pattern for exakat';
                    return self::NOT_LOADED;
                }
            }

            // same as inside the loop, but this means that exakat was not found
            if ($this->config['codacy_analyzers'] === 'all') {
                $this->config['codacy_error'] = '.codacy.json lists no tool configuration for exakat';
                return self::NOT_LOADED;
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