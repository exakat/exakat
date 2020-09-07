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
use Exakat\Vcs\Vcs;

class ProjectConfig extends Config {
    private $projects_root = '.';
    private $project       = '';

    protected $config = array('phpversion'          => PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION,
                              'project_name'        => '',
                              'project_url'         => '',
                              'project_vcs'         => 'git',
                              'project_description' => '',
                              'project_branch'      => '',
                              'project_tag'         => '',
// No default value,
//                              'project_rulesets'    => array(),
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
                              'include_dirs'        => array(),
                              'ignore_dirs'         => array(),
                              'ignore_rules'        => array(),
                              'stubs'               => array(),
                              );

    public function __construct(string $projects_root) {
        $this->projects_root = "$projects_root/projects/";
    }

    public function setProject(Project $project): void {
        $this->project = $project;
    }

    public function loadConfig(Project $project) : ?string {
        $this->project = $project;

        $pathToIni = "{$this->projects_root}{$project}/config.ini";
        if (!file_exists($pathToIni)) {
            $this->config = array_filter($this->config);
            display("No config.ini for project named '$project'\n");

            return self::NOT_LOADED;
        }

        $ini = parse_ini_file($pathToIni, \INI_PROCESS_SECTIONS);
        if (!is_array($ini)) {
            $error = error_get_last();
            display("Couldn't parse $pathToIni : $error[message]\nIgnoring file\n");

            $this->config = array_filter($this->config);

            return self::NOT_LOADED;
        }

        foreach(array_keys($this->config) as $key) {
            if (!isset($ini[$key])) {
                $ini[$key] = $this->config[$key];
            }
        }

        // Aliasing project_themes into rulesets
        if (isset($ini['project_themes'])) {
            print "rename project_themes in project_rulesets, in your config.ini file\n";

            if (empty($this->config['project_rulesets'])) {
                $this->config['project_rulesets'] = $ini['project_themes'];
            }
        }
        $this->config = $ini;

        $pathToCache = "{$this->projects_root}{$project}/config.cache";
        if (file_exists($pathToCache)) {
            $iniCache = parse_ini_file($pathToCache);
            if (isset($iniCache['ignore_dirs'])) {
                $this->config['ignore_dirs'] = array_merge($this->config['ignore_dirs'],
                                                           $iniCache['ignore_dirs']);
            }
        }

        $this->config['project_vcs'] = $this->config['project_vcs'] ?? '';

        // Default behavior to keep exakat running until everyone has a filled file_extension option in config.ini
        if (empty($this->config['file_extensions'])) {
            $this->config['file_extensions'] = 'php,php3,inc,tpl,phtml,tmpl,phps,ctp,module';
        }

        // Converting the string format to arrays when necessary
        if (isset($this->config['other_php_versions']) &&
            is_string($this->config['other_php_versions'])) {
            $this->config['other_php_versions'] = listToArray($this->config['other_php_versions']);
            foreach($this->config['other_php_versions'] as &$version) {
                $version = trim($version, '. ');
            }
            unset($version);
        }

        if (isset($this->config['file_extensions']) &&
            is_string($this->config['file_extensions'])) {
            $this->config['file_extensions'] = listToArray($this->config['file_extensions']);
            foreach($this->config['file_extensions'] as &$ext) {
                $ext = trim($ext, '. ');
            }
            unset($ext);
        }

        if (!isset($this->config['phpversion']) ||
             $this->config['phpversion'] === 'PHP' ||
             !in_array($this->config['phpversion'], Phpexec::VERSIONS)) {
            $this->config['phpversion'] = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
        }
        // else ALL is good

        if (isset($this->config['project_reports']) &&
            is_string($this->config['project_reports'])) {
            $this->config['project_reports'] = listToArray($this->config['project_reports']);
            foreach($this->config['project_reports'] as &$ext) {
                $ext = trim($ext);
            }
            unset($ext);
        }

        if (isset($this->config['project_rulesets'])) {
            if (is_string($this->config['project_rulesets'])) {
                $this->config['project_rulesets'] = listToArray($this->config['project_rulesets']);
            }

            $this->config['project_rulesets'] = array_filter($this->config['project_rulesets'] ?? array());
            $this->config['project_rulesets'] = array_map('trim', $this->config['project_rulesets']);
            $this->config['project_rulesets'] = array_unique($this->config['project_rulesets']);
        }

        if (in_array($this->config['project_vcs'], Vcs::SUPPORTED_VCS)) {
            $this->config['git'] = false; // remove Git, which is by default
            $this->config[$this->config['project_vcs']] = true; // potentially, revert git
        }

        // Calculate the stubs recursivement if it is a folder
        // all path are relative to the project_dir/code, cannot be outside.
        $stubs = array(array());
        $code_dir="{$this->projects_root}{$project}/code/";
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
                $allFiles = array_map(function (string $path) use ($stub): string { return $stub . ltrim($path, '.'); }, $allFiles);
                chdir($d);

                $stubs[$stub] = $allFiles;
            }
        }
        $this->config['stubs'] = array_unique(array_merge(...array_values($stubs)));

        // remove unset values
        $this->config = array_filter($this->config);

        return "$project/config.ini";
    }

    // required for Init Project
    public function setConfig(string $name, $value): void {
        $this->config[$name] = $value;
    }

    public function getConfig(string $dir_root = ''): string {
        // $vendor
        if ($this->config['include_dirs'] === array('/')) {
            $include_dirs = 'include_dirs[] = "";';
        } else {
            $include_dirs = 'include_dirs[] = "' . implode("\";\ninclude_dirs[] = \"", $this->config['include_dirs']) . "\";\n";
        }
        $ignore_dirs   = self::makeIniArray('ignore_dirs', $this->config['ignore_dirs']);
        $ignore_rules  = self::makeIniArray('ignore_rules', $this->config['ignore_rules'] ?? array());

        $file_extensions  = implode(',', $this->config['file_extensions']);

        $custom_configs = array();

        $iniFiles = glob("$dir_root/human/en/*/*.ini");
        $default = array();
        foreach($iniFiles as $file) {
            $ini = parse_ini_file($file, \INI_PROCESS_SECTIONS);
            if (isset($ini['parameter1']['default'])) {
                $default[basename(dirname($file)) . '/' . basename($file, '.ini')][$ini['parameter1']['name']] = $ini['parameter1']['default'];
            }
        }

        foreach($this->config as $key => $value) {
            if (strpos($key, '/') === false) {
                continue;
            }

            $cc = "[$key]\n";
            foreach($value as $name => $values) {
                if (is_array($values)) {
                    $cc .= "{$name}[] = " . implode(";\n{$name}[] = ", $values) . ";\n; default = {$default[$key][$name]}\n";
                } elseif (is_string($values)) {
                    if (intval($values) === 0) {
                        $cc .= "{$name} = \"$values\";\n; default = {$default[$key][$name]}\n";
                    } else {
                        $cc .= "{$name} = $values;\n; default = {$default[$key][$name]}\n";
                    }
                } elseif (is_int($values)) {
                    $cc .= "{$name} = $values;\n; default = {$default[$key][$name]}\n";
                } else {
                    assert(false, 'Unknown type for INI creation : ' . gettype($values));
                }

                unset($default[$key]);
            }
            $cc .= PHP_EOL;

            $custom_configs[] = $cc;
        }

        foreach($default as $key => $value) {
            $cc2 = "[$key]\n";
            foreach($value as $name => $values) {
                if (is_array($values)) {
                    $cc2 .= "{$name}[] = " . implode(";\n{$name}[] = ", $values) . ";\n; default value\n\n";
                } elseif (is_string($values)) {
                    if (intval($values) === 0) {
                        $cc2 .= "{$name} = \"$values\";\n; default value\n\n";
                    } else {
                        $cc2 .= "{$name} = $values;\n; default value\n\n";
                    }
                } elseif (is_int($values)) {
                    $cc2 .= "{$name} = $values;\n; default value\n\n";
                } else {
                    assert(false, 'Unknown type for INI creation : ' . gettype($values));
                }
            }
            $custom_configs[] = $cc2;
        }

        $custom_configs = implode('', $custom_configs);

        $configIni = <<<INI
;Main PHP version for this code.
;default is to use config/exakat.ini
;phpversion = {$this->config['phpversion']}

;Ignored dirs and files, relative to code source root.
$ignore_dirs

;Ignored rules.
$ignore_rules

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

        return $configIni;
    }

    private static function makeIniArray(string $name, array $array): string {
        return $name . '[] = "' . implode("\";\n{$name}[] = \"", $array) . "\";\n";
    }
}

?>