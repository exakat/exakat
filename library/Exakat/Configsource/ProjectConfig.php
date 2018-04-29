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
    private $project = '';

    protected $config = array('phpversion'          => PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION,
                              'project_name'        => '',
                              'project_url'         => '',
                              'project_vcs'         => 'git',
                              'project_description' => '',
                              'project_branch'      => '',
                              'project_tag'         => '',
                              'file_extensions'     => array('php', 
                                                             'php3', 
                                                             'inc', 
                                                             'tpl', 
                                                             'phtml', 
                                                             'tmpl', 
                                                             'phps', 
                                                             'ctp', 
                                                             'module',
                                                             ),
                              'include_dirs'        => array('/',
                                                            ),
                              'ignore_dirs'         => array('/assets',
                                                             '/cache',
                                                             '/css',
                                                             '/data',
                                                             '/doc',
                                                             '/docker',
                                                             '/docs',
                                                             '/example',
                                                             '/examples',
                                                             '/images',
                                                             '/js',
                                                             '/lang',
                                                             '/spec',
                                                             '/sql',
                                                             '/test',
                                                             '/tests',
                                                             '/tmp',
                                                             '/version',
                                                             '/var',
                                                            ),
                              );
    
    public function __construct($projects_root) {
        $this->projects_root = $projects_root.'/projects/';
    }
    
    public function setProject($project) {
        $this->project = $project;
    }

    public function loadConfig($project) {
        $this->project = $project;

        $pathToIni = "{$this->projects_root}{$project}/config.ini";
        if (!file_exists($pathToIni)) {
            return self::NOT_LOADED;
        }

        $this->config = parse_ini_file($pathToIni, true);

        $pathToCache = "{$this->projects_root}{$project}/config.cache";
        if (file_exists($pathToCache)) {
            $iniCache = parse_ini_file($pathToCache);
            if ($iniCache !== null) {
                $this->config = array_merge($this->config,
                                            $iniCache);
            }
        }

        // removing empty values in the INI file
        /*
        foreach($this->config as $id => &$value) {
            if (is_array($value) && empty($value[0])) {
                unset($value[0]);
            } elseif (empty($value)) {
                unset($this->config[$id]);
            }
        }
        unset($value);
        */
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

    public function setConfig($name, $value) {
        $this->config[$name] = $value;
    }
    
    public function writeConfig() {
        // $vendor
        $include_dirs = 'include_dirs[] = '.implode(";\ninclude_dirs[] = ", $this->config['include_dirs']).";\n";
        $ignore_dirs  = 'ignore_dirs[] = '.implode(";\nignore_dirs[] = ", $this->config['ignore_dirs']).";\n";

        $file_extensions  = '.'.implode('.', $this->config['file_extensions']);
        
        $custom_configs = array();
        
        foreach($this->config as $key => $value) {
            if (strpos($key, '/') === false) {
                continue;
            }
            
            $cc = "[$key]\n";
            foreach($value as $name => $values) {
                $cc .= "{$name}[] = ".implode(";\n{$name}[] = ", $values).";\n\n";
            }
            
            $cc .= PHP_EOL;
            
            $custom_configs[] = $cc;
        }
        
        $custom_configs = implode('', $custom_configs);

        $configIni = <<<INI
;Main PHP version for this code.
phpversion = {$this->config['phpversion']}

;Ignored dirs and files, relative to code source root.
$ignore_dirs

;Included dirs or files, relative to code source root. Default to all.
;Those are added after ignoring directories
$include_dirs

;Accepted file extensions
file_extensions = $file_extensions

;Description of the project
project_name        = "{$this->config['project_name']}";
project_url         = "{$this->config['project_url']}";
project_vcs         = "{$this->config['project_vcs']}";
project_description = "{$this->config['project_description']}";
project_branch      = "{$this->config['project_branch']}";
project_tag         = "{$this->config['project_tag']}";

$custom_configs

INI;

        file_put_contents($this->projects_root.$this->project.'/config.ini', $configIni);    
    }
}

?>