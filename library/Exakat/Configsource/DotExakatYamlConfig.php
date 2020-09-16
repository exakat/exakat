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

use Exakat\Phpexec;
use Exakat\Project;
use Exakat\Config as Configuration;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class DotExakatYamlConfig extends Config {
    const YAML_FILE = '.exakat.yml';
    private $dotExakatYaml = '';
    private $rulesets = array();

    public function __construct() {
        $this->dotExakatYaml = getcwd() . '/' . self::YAML_FILE;

        if (!file_exists($this->dotExakatYaml)) {
            $secondary = substr($this->dotExakatYaml, 0, -3) . 'yaml';
            if (file_exists($secondary)) {
                $this->dotExakatYaml = $secondary;
            }
        }
    }

    public function loadConfig(Project $project) : ?string {
        if (!file_exists($this->dotExakatYaml)) {
            $this->config['inside_code'] = Configuration::WITH_PROJECTS;
            return self::NOT_LOADED;
        }

        try {
            $tmp_config = Yaml::parseFile($this->dotExakatYaml);
        } catch (ParseException $exception) {
            print 'Error while parsing ' . basename($this->dotExakatYaml) . '. Configuration ignored.' . PHP_EOL;

            return self::NOT_LOADED;
        }

        if (!is_array($tmp_config)) {
            // Can't use display while in config phase
            display("Failed to parse YAML file. Please, check its syntax.\n");
            return self::NOT_LOADED;
        }

        // removing empty values in the INI file
        foreach($tmp_config as &$value) {
            if (is_array($value) && empty($value[0])) {
                unset($value[0]);
            }
        }
        unset($value);

        $other_php_versions = array();
        foreach(Configuration::PHP_VERSIONS as $version) {
            $phpVersion = "php$version";
            if (empty($this->config->{$phpVersion})) {
                continue;
            }
            $php = new Phpexec($version[0] . '.' . $version[1], $this->config->{$phpVersion});
            if ($php->isValid()) {
                $other_php_versions[] = $version;
            }
        }

        // check and default values
        $defaults = array( 'other_php_versions' => $other_php_versions,
                           'phpversion'         => substr(PHP_VERSION, 0, 3),
                           'file_extensions'    => array('php', 'php3', 'inc', 'tpl', 'phtml', 'tmpl', 'phps', 'ctp', 'module'),
                           'project_rulesets'   => 'CompatibilityPHP53,CompatibilityPHP54,CompatibilityPHP55,CompatibilityPHP56,CompatibilityPHP70,CompatibilityPHP71,CompatibilityPHP72,CompatibilityPHP73,CompatibilityPHP74,Dead code,Security,Analyze,Preferences,Appinfo,Appcontent',
                           'project_reports'    => array('Text'),
                           'ignore_dirs'        => array('/assets',
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
                           'include_dirs'        => array(),
                           'rulesets'            => array(),
                           'project'             => null,
                           'project_name'        => '',
                           'project_url'         => '',
                           'project_vcs'         => '',
                           'project_description' => '',
                           'project_branch'      => '',
                           'project_tag'         => '',

                           'stubs'               => array(),

                           'ignore_rules'        => array(),
                        );

        $this->config['inside_code'] = Configuration::INSIDE_CODE;

        foreach($defaults as $name => $default_value) {
            $this->config[$name] = empty($tmp_config[$name]) ? $default_value : $tmp_config[$name];
            unset($tmp_config[$name]);
        }

        if (isset($tmp_config['project_themes'])) {
            display("please, rename project_themes into project_rulesets in your .exakat.yaml file\n");

            if (empty($this->config['project_rulesets'])) {
                $this->config['project_rulesets'] = $this->config['project_themes'];
            }
        }

        if (is_string($this->config['other_php_versions'])) {
            $this->config['other_php_versions'] = listToArray($this->config['other_php_versions']);
            foreach($this->config['other_php_versions'] as &$version) {
                $version = str_replace('.', '', trim($version));
            }
            unset($version);
        }

        if (is_string($this->config['file_extensions'])) {
            $this->config['file_extensions'] = listToArray($this->config['file_extensions']);
            foreach($this->config['file_extensions'] as &$ext) {
                $ext = trim($ext, '. ');
            }
            unset($ext);
        }

        if (is_string($this->config['project_reports'])) {
            $this->config['project_reports'] = listToArray($this->config['project_reports']);
            foreach($this->config['project_reports'] as &$ext) {
                $ext = trim($ext);
            }
            unset($ext);
        }

        if (is_string($this->config['project_rulesets'])) {
            $this->config['project_rulesets'] = listToArray($this->config['project_rulesets']);
            foreach($this->config['project_rulesets'] as &$ext) {
                $ext = trim($ext);
            }
            unset($ext);
        }

        if (isset($this->config['project'])) {
            $this->config['project'] = new Project($this->config['project']);
        } elseif (isset($this->config['project_name'])) {
            $this->config['project'] = new Project(mb_strtolower(preg_replace('/\W/', '_', $this->config['project_name'] )));
        } else {
            $this->config['project'] = new Project('in-code-audit');
        }
        if (isset($this->config['rulesets'])) {
            // clean the read
            $this->rulesets = RulesetConfig::cleanRulesets($this->config['rulesets']);

            unset($this->config['rulesets']);
        }

        foreach($tmp_config as $name => $tmp) {
            if (class_exists('Exakat\\Analyzer\\' . str_replace('/', '\\', $name))) {
                $this->config[$name] = $tmp;
                unset($tmp_config[$name]);
            }
        }

        if (!empty($tmp_config)) {
            display('Ignoring ' . count($tmp_config) . ' unkown directives : ' . implode(', ', array_keys($tmp_config)));
        }

        // Collect stubs. Stubs MUST be in the same code repository, so they are chrooted with the current directory.
        $stubs = array();
        $code_dir = getcwd();
        $this->config['stubs'] = makeArray($this->config['stubs']);
        foreach($this->config['stubs'] as $stub) {
            $d = getcwd();
            $path = realpath($code_dir . $stub);
            if ($path === false) {
                continue;
            }

            if (!file_exists($path)) {
                $stubs[$stub] = array();

                continue;
            }

            if (is_file($path)) {
                $stubs[$stub] = array($stub);

                continue;
            }

            if (is_dir($path)) {
                chdir($path);
                $allFiles = rglob('.');
                $allFiles = array_map(function ($path) use ($stub) { return $stub . ltrim($path, '.'); }, $allFiles);
                chdir($d);

                $stubs[$stub] = $allFiles;
            }
        }
        $this->config['stubs'] = array_unique(array_merge(...array_values($stubs)));

        return self::YAML_FILE;
    }

    public function getRulesets() {
        return $this->rulesets;
    }

    public function getConfig(string $dir_root = '') : string {
        // $vendor
        if ($this->config['include_dirs'] === array('/')) {
            $include_dirs = 'include_dirs[] = "";';
        } else {
            $include_dirs = 'include_dirs[] = "' . implode("\";\ninclude_dirs[] = \"", $this->config['include_dirs']) . "\";\n";
        }
        $ignore_dirs  = 'ignore_dirs[] = "' . implode("\";\nignore_dirs[] = \"", $this->config['ignore_dirs']) . "\";\n";
        $file_extensions  = implode(',', $this->config['file_extensions']);

        $custom_configs = array();

        $default = array();
        $iniFiles = glob("$dir_root/human/en/*/*.ini");
        foreach($iniFiles as $file) {
            $ini = parse_ini_file($file, \INI_PROCESS_SECTIONS);
            if (isset($ini['parameter1'])) {
                $default[basename(dirname($file)) . '/' . basename($file, '.ini')][$ini['parameter1']['name']] = $ini['parameter1']['default'];
            }
        }

        foreach($this->config as $key => $value) {
            if (strpos($key, '/') === false) {
                continue;
            }

            foreach($value as $name => $values) {
                if (isset($default[$key])) {
                    $default[$key][$name] = $values;
                }
            }
        }

        $custom_configs = implode('', $custom_configs);

        $configIni = array(
'project'             => "{$this->config['project']}",
'project_name'        => "{$this->config['project_name']}",
'project_url'         => "{$this->config['project_url']}",
'project_vcs'         => "{$this->config['project_vcs']}",
'project_description' => "{$this->config['project_description']}",
'project_branch'      => "{$this->config['project_branch']}",
'project_tag'         => "{$this->config['project_tag']}",
'include_dirs'        => $this->config['include_dirs'],
'ignore_dirs'         => $this->config['ignore_dirs'],
'ignore_rules'        => $this->config['ignore_rules'],
'file_extensions'     => $file_extensions,
'custom'              => $default,
        );

        return Yaml::dump($configIni);
    }

}

?>