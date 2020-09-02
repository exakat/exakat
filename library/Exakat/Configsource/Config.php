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
use Symfony\Component\Yaml\Yaml as Symfony_Yaml;

abstract class Config {
    const NOT_LOADED = null;

    protected $config  = array();
    protected $options = array();

    abstract public function loadConfig(Project $project) : ?string ;

    public function toArray(): array {
        return $this->config;
    }

    public function get($index) {
        return $this->config[$index] ?? null;
    }

    public function toIni(): string {
        $ini = array();

        $ini[] = ';Main PHP version for this code.';
        $ini[] = "phpversion = {$this->options['phpversion']}";
        $ini[] = '';

        $ini[] = ';Ignored dirs and files, relative to code source root.';
        foreach($this->ignore_dirs as $ignore_dir) {
            $ini[] = "ignore_dirs[] = \"$ignore_dir\"";
        }
        $ini[] = '';

        $ini[] = ';Included dirs or files, relative to code source root. Default to all.';
        $ini[] = ';Those are added after ignoring directories';
        foreach($this->include_dirs as $include_dir) {
            $ini[] = "include_dirs[] = \"$include_dir\"";
        }
        $ini[] = '';

        $ini[] = ';Accepted file extensions';
        $ini[] = 'file_extensions = "' . implode(',', $this->file_extensions) . '"';
        $ini[] = '';

        $ini[] = ';Stub files and folders';
        if (empty($this->stubs)) {
            $ini[] = "stub[] = '';";
        } else {
            foreach($this->stubs as $stub) {
                $ini[] = "stub[] = \"$stub\"";
            }

        }
        $ini[] = '';

        $ini[] = ';Ignored rules';
        foreach($this->ignore_rules as $ignore_rule) {
            $ini[] = "ignore_rules[] = \"$ignore_rule\"";
        }
        $ini[] = '';

        $ini[] = ';Description of the project';
        $ini[] = "project_name        = \"{$this->project_name}\";";
        $ini[] = "project_url         = \"{$this->project_url}\";";
        $ini[] = "project_vcs         = \"{$this->project_vcs}\";";
        $ini[] = "project_description = \"{$this->project_description}\";";
        $ini[] = "project_branch      = \"{$this->project_branch}\";";
        $ini[] = "project_tag         = \"{$this->project_tag}\";";
        $ini[] = '';

        $parameters = preg_grep('#^[A-Z][^/]+/[A-Z].+$#', array_keys($this->options));
        foreach($parameters as $parameter) {
            $class = "\Exakat\Analyzer\\" . str_replace('/', '\\', $parameter);
            if (!class_exists($class)) {
                continue;
            }
            $ini[] = "[$parameter]";
            foreach($this->options[$parameter] as $name => $value) {
                if (!property_exists($class, $name)) {
                    continue;
                }
                $ini[] = "$name = $value;";
            }
            $ini[] = '';
        }

        return implode(PHP_EOL, $ini);
    }

    public function toYaml(): string {
        $yaml = array('phpversion'          => $this->options['phpversion'],
                      'ignore_dirs'         => $this->ignore_dirs,
                      'include_dirs'        => $this->include_dirs,
                      'ignore_rules'        => $this->ignore_rules,
                      'file_extensions'     => $this->file_extensions,
                      'stub'                => $this->stubs,
                      'project_name'        => $this->project_name,
                      'project_url'         => $this->project_url,
                      'project_vcs'         => $this->project_vcs,
                      'project_description' => $this->project_description,
                      'project_branch'      => $this->project_branch,
                      'project_tag'         => $this->project_tag,
                      );

        $parameters = preg_grep('#^[A-Z][^/]+/[A-Z].+$#', array_keys($this->options));
        foreach($parameters as $parameter) {
            $class = "\Exakat\Analyzer\\" . str_replace('/', '\\', $parameter);
            if (!class_exists($class)) {
                continue;
            }
            $yaml[$parameter] = array();
            foreach($this->options[$parameter] as $name => $value) {
                if (!property_exists($class, $name)) {
                    continue;
                }
                $yaml[$parameter][$name] = $value;
            }
        }

        return Symfony_Yaml::dump($yaml);
    }
}

?>