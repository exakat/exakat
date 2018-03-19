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

class ProjectConfig extends Config {
    private $projects_root = '.';
    
    public function __construct($projects_root) {
        $this->projects_root = $projects_root.'/projects/';
    }

    public function loadConfig($project) {
        $pathToIni = "{$this->projects_root}{$project}/config.ini";
        if (!file_exists($pathToIni)) {
            return self::NOT_LOADED;
        }

        $this->config = parse_ini_file($pathToIni);

        $pathToCache = "{$this->projects_root}{$project}/config.cache";
        if (file_exists($pathToCache)) {
            $iniCache = parse_ini_file($pathToCache);
            if ($iniCache !== null) {
                $this->config = array_merge($this->config,
                                            $iniCache);
            }
        }

        // removing empty values in the INI file
        foreach($this->config as $id => &$value) {
            if (is_array($value) && empty($value[0])) {
                unset($value[0]);
            } elseif (empty($value)) {
                unset($this->config[$id]);
            }
        }
        unset($value);
        $this->config['project_vcs'] = $this->config['project_vcs'] ?? '';
        
        // Converting the string format to arrays when necessary
        if (isset($this->config['other_php_versions']) && 
            is_string($this->config['other_php_versions'])) {
            $this->config['other_php_versions'] = explode(',', $this->config['other_php_versions']);
            foreach($this->config['other_php_versions'] as &$version) {
                $version = str_replace('.', '', trim($version));
            }
            unset($version);
        }

        if (isset($this->config['file_extensions']) && 
            is_string($this->config['file_extensions'])) {
            $this->config['file_extensions'] = explode(',', $this->config['file_extensions']);
            foreach($this->config['file_extensions'] as &$ext) {
                $ext = trim($ext, '. ');
            }
            unset($ext);
        }

        if (isset($this->config['project_reports']) && 
            is_string($this->config['project_reports'])) {
            $this->config['project_reports'] = explode(',', $this->config['project_reports']);
            foreach($this->config['project_reports'] as &$ext) {
                $ext = trim($ext);
            }
            unset($ext);
        }

        if (isset($this->config['project_themes']) && 
            is_string($this->config['project_themes'])) {
            $this->config['project_themes'] = explode(',', $this->config['project_themes']);
            foreach($this->config['project_themes'] as &$ext) {
                $ext = trim($ext);
            }
            unset($ext);
        }
        
        if (in_array($this->config['project_vcs'], array('git', 'svn', 'bzr', 'hg', 'composer', 'tgz', 'tbz', 'zip', ))) {
            $this->config['git'] = false; // remove Git, which is by default
            $this->config[$this->config['project_vcs']] = true; // potentially, revert git
        }

        return "$project/config.ini";
    }
}

?>