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

use Exakat\Phpexec;
use Exakat\Config as Configuration;
use Symfony\Component\Yaml\Yaml;

class DotExakatYamlConfig extends Config {
    private $dotExakatYaml = '';

    public function __construct() {
        $this->dotExakatYaml = getcwd() . '/.exakat.yml';
    }

    public function loadConfig($project) {
        if (!file_exists($this->dotExakatYaml)) {
            $this->config['inside_code'] = Configuration::WITH_PROJECTS;
            return self::NOT_LOADED;
        }

        $this->config = Yaml::parseFIle($this->dotExakatYaml);
        print_r($this->config);die();

        // removing empty values in the INI file
        foreach($this->config as &$value) {
            if (is_array($value) && empty($value[0])) {
                unset($value[0]);
            }
        }
        unset($value);

        $other_php_versions = array();
        foreach(Configuration::PHP_VERSIONS as $version) {
            if (empty($this->configFile['php' . $version])) {
                continue;
            }
            $php = new Phpexec($version[0] . '.' . $version[1], $this->configFile["php$version"]);
            if ($php->isValid()) {
                $other_php_versions[] = $version;
            }
        }
    
        // check and default values
        $defaults = array( 'ignore_dirs'        => array('/test', '/tests', '/Tests', '/Test', '/example', '/examples', '/docs', '/doc', '/tmp', '/version', '/vendor', '/js', '/lang', '/data', '/css', '/cache', '/vendor', '/assets', '/spec', '/sql'),
                           'other_php_versions' => $other_php_versions,
                           'phpversion'         => substr(PHP_VERSION, 0, 3),
                           'file_extensions'    => array('php', 'php3', 'inc', 'tpl', 'phtml', 'tmpl', 'phps', 'ctp', 'module'),
                           'project_themes'     => 'CompatibilityPHP53,CompatibilityPHP54,CompatibilityPHP55,CompatibilityPHP56,CompatibilityPHP70,CompatibilityPHP71,CompatibilityPHP72,CompatibilityPHP73,CompatibilityPHP74,Dead code,Security,Analyze,Preferences,Appinfo,Appcontent',
                           'project_reports'    => array('Text'),
                        );

        $this->config['inside_code'] = Configuration::INSIDE_CODE;

        foreach($defaults as $name => $value) {
            if (empty($this->config[$name])) {
                $this->config[$name] = $value;
            }
        }

        if (is_string($this->config['other_php_versions'])) {
            $this->config['other_php_versions'] = explode(',', $this->config['other_php_versions']);
            foreach($this->config['other_php_versions'] as &$version) {
                $version = str_replace('.', '', trim($version));
            }
            unset($version);
        }

        if (is_string($this->config['file_extensions'])) {
            $this->config['file_extensions'] = explode(',', $this->config['file_extensions']);
            foreach($this->config['file_extensions'] as &$ext) {
                $ext = trim($ext, '. ');
            }
            unset($ext);
        }

        if (is_string($this->config['project_reports'])) {
            $this->config['project_reports'] = explode(',', $this->config['project_reports']);
            foreach($this->config['project_reports'] as &$ext) {
                $ext = trim($ext);
            }
            unset($ext);
        }

        if (is_string($this->config['project_themes'])) {
            $this->config['project_themes'] = explode(',', $this->config['project_themes']);
            foreach($this->config['project_themes'] as &$ext) {
                $ext = trim($ext);
            }
            unset($ext);
        }

        return '.exakat.ini';
    }
}

?>