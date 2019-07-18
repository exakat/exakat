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

namespace Exakat;

use Symfony\Component\Yaml\Yaml as Symfony_Yaml;
use Exakat\Configsource\{CommandLine, DefaultConfig, DotExakatConfig, DotExakatYamlConfig, EmptyConfig, EnvConfig, ExakatConfig, ProjectConfig, RemoteConfig, ThemaConfig, Config as Configsource };
use Exakat\Exceptions\InaptPHPBinary;
use Exakat\Reports\Reports;
use Exakat\Autoload\AutoloadDev;
use Exakat\Autoload\AutoloadExt;
use Phar;

class Config {
    const PHP_VERSIONS = array('52', '53', '54', '55', '56', '70', '71', '72', '73', '74', '80',);

    const INSIDE_CODE   = true;
    const WITH_PROJECTS = false;
    
    const IS_PHAR      = true;
    const IS_NOT_PHAR  = false;

    public $dir_root              = '.';
    public $ext_root              = '.';
    public $projects_root         = '.';
    public $is_phar               = true;
    public $executable            = '';
    public $ext                   = null;
    public $dev                   = null;

    private $projectConfig         = null;
    private $commandLineConfig     = null;
    private $defaultConfig         = null;
    private $exakatConfig          = null;
    private $dotExakatConfig       = null;
    private $dotExakatYamlConfig   = null;
    private $envConfig             = null;
    private $argv                  = null;
    private $screen_cols           = 100;

    private $configFiles = array();
    private $options     = array();
    private $remotes     = array();
    private $themas      = array();
    
    public function __construct($argv) {
        $this->argv = $argv;

        $this->is_phar  = class_exists('\\Phar') && !empty(phar::running()) ? self::IS_PHAR : self::IS_NOT_PHAR;
        if ($this->is_phar === self::IS_PHAR) {
            $this->executable    = $_SERVER['SCRIPT_NAME'];
            $this->projects_root = substr(dirname(phar::running()), 7);
            $this->dir_root      = phar::running();
            $this->ext_root      = substr(dirname(phar::running()) . '/ext', 5);

            assert_options(ASSERT_ACTIVE, 0);

            error_reporting(0);
            ini_set('display_errors', 0);
            if (!file_exists("{$this->projects_root}/projects")) {
                mkdir("{$this->projects_root}/projects", 0755);
            }
            ini_set('error_log', "{$this->projects_root}/projects/php_error.log");
        } else {
            $this->executable    = $_SERVER['SCRIPT_NAME'];
            $this->dir_root      = dirname(__DIR__, 2);
            // Run projects in the working directory
            if (dirname($_SERVER['SCRIPT_FILENAME']) === 'bin'      &&
                dirname($_SERVER['SCRIPT_FILENAME'], 2) === 'vendor') {
                $this->projects_root = getcwd();
            } else {
                $this->projects_root = dirname(__DIR__, 2);
            }
            $this->ext_root      = "{$this->dir_root}/ext";


            assert_options(ASSERT_ACTIVE, 1);
            assert_options(ASSERT_BAIL, 1);

            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }

        unset($argv[0]);

        $this->defaultConfig = new DefaultConfig($this->projects_root);

        $this->exakatConfig = new ExakatConfig($this->projects_root);
        if ($file = $this->exakatConfig->loadConfig(null)) {
            $this->configFiles[] = $file;
        }
        
        // then read the config from the commandline (if any)
        $this->commandLineConfig = new CommandLine();
        $this->commandLineConfig->loadConfig($argv);

        $this->envConfig = new EnvConfig();
        if ($file = $this->envConfig->loadConfig(null)) {
            $this->configFiles[] = $file;
        }

        // then read the config for the project in its folder
        if ($this->commandLineConfig->get('project') === null) {
            $this->projectConfig   = new EmptyConfig();

            $this->dotExakatConfig = new DotExakatConfig();
            if (($file = $this->dotExakatConfig->loadConfig(null)) === Configsource::NOT_LOADED) {
                $this->dotExakatYamlConfig = new DotExakatYamlConfig();
                $file = $this->dotExakatYamlConfig->loadConfig(null);
                if ($file !== Configsource::NOT_LOADED) {
                    $this->configFiles[] = $file;
                }
            } else {
                $this->configFiles[] = $file;
                $this->dotExakatYamlConfig = new EmptyConfig();
            }

        } else {
            $this->projectConfig = new ProjectConfig($this->projects_root);
            if ($file = $this->projectConfig->loadConfig($this->commandLineConfig->get('project'))) {
                $this->configFiles[] = $file;
            }

            $this->dotExakatConfig     = new EmptyConfig();
            $this->dotExakatYamlConfig = new EmptyConfig();
        }
        
        // build the actual config. Project overwrite commandline overwrites config, if any.
        $this->options = array_merge($this->defaultConfig->toArray(),
                                     $this->exakatConfig->toArray(),
                                     $this->envConfig->toArray(),
                                     $this->projectConfig->toArray(),
                                     $this->dotExakatConfig->toArray(),
                                     $this->dotExakatYamlConfig->toArray(),
                                     $this->commandLineConfig->toArray()
                                     );
        $this->options['configFiles'] = $this->configFiles;
        
        $remote = new RemoteConfig($this->projects_root);
        if ($file = $remote->loadConfig($this->commandLineConfig->get('project'))) {
            $this->configFiles[] = $file;
            $this->remotes = $remote->toArray();
        }

        $themas = new ThemaConfig($this->projects_root);
        if ($file = $themas->loadConfig($this->commandLineConfig->get('project'))) {
            $this->configFiles[] = $file;
            $this->themas = $themas->toArray();
        }
        
        if ($this->dotExakatYamlConfig instanceof DotExakatYamlConfig) {
            $this->themas = array_merge($this->themas, $this->dotExakatYamlConfig->getThemas());
        }

        if ($this->options['command'] !== 'doctor') {
            $this->checkSelf();
        }

        // autoload dev
        $this->dev = new AutoloadDev($this->extension_dev);
        $this->dev->registerAutoload();

        // autoload extensions
        $this->ext = new AutoloadExt($this->ext_root);
        $this->ext->registerAutoload();
        
        $this->finishConfigs();
    }
    
    private function finishConfigs() {
        $this->options['pid'] = getmypid();

        if ($this->options['inside_code'] === self::INSIDE_CODE) {
            $this->options['project_dir']   = getcwd();
            $this->options['code_dir']      = getcwd();
            $this->options['log_dir']       = getcwd() . '/.exakat';
            $this->options['tmp_dir']       = getcwd() . '/.exakat';
            $this->options['datastore']     = getcwd() . '/.exakat/datastore.sqlite';
            $this->options['dump']          = getcwd() . '/.exakat/dump.sqlite';
            $this->options['dump_previous'] = getcwd() . '/.exakat/dump-1.sqlite';
            $this->options['dump']          = getcwd() . '/.exakat/dump.sqlite';
            $this->options['dump_tmp']      = getcwd() . '/.exakat/.dump.sqlite';
        } else {
            $this->options['project_dir']   = $this->projects_root . '/projects/' . $this->options['project'];
            $this->options['code_dir']      = $this->options['project_dir'] . '/code';
            $this->options['log_dir']       = $this->options['project_dir'] . '/log';
            $this->options['tmp_dir']       = $this->options['project_dir'] . '/.exakat';
            $this->options['datastore']     = $this->options['project_dir'] . '/datastore.sqlite';
            $this->options['dump_previous'] = $this->options['project_dir'] . '/dump-1.sqlite';
            $this->options['dump_tmp']      = $this->options['project_dir'] . '/.dump.sqlite';
            $this->options['dump']          = $this->options['project_dir'] . '/dump.sqlite';
        }
    }

    public function __isset($name) {
        return isset($this->options[$name]);
    }

    public function __get($name) {
        if ($name === 'configFiles') {
            $return = $this->configFiles;
        } elseif ($name === 'remotes') {
            $return = $this->remotes;
        } elseif ($name === 'themas') {
            $return = $this->themas;
        } elseif (isset($this->options[$name])) {
            $return = $this->options[$name];
        } elseif ($name === 'screen_cols') {
            $return = $this->screen_cols;
        } else {
//            debug_print_backtrace();
//            assert(false, "No such config property as '$name'");
            $return = null;
        }

        return $return;
    }

    public function __set($name, $value) {
        display("It is not possible to modify configuration $name with value '$value'\n");
    }

    private function checkSelf() {
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            throw new InaptPHPBinary('PHP needs to be version 7.0.0 or more to run exakat.(' . PHP_VERSION . ' provided)');
        }
        $extensions = array('curl', 'mbstring', 'sqlite3', 'hash', 'json');
        
        $missing = array();
        foreach($extensions as $extension) {
            if (!extension_loaded($extension)) {
                $missing[] = $extension;
            }
        }
        
        if (!empty($missing)) {
           throw new InaptPHPBinary('PHP needs ' . (count($missing) == 1 ? 'one' : count($missing)) . ' extension' . (count($missing) > 1 ? 's' : '') . ' with the current version : ' . implode(', ', $missing));
        }
    }

    public function commandLineJson() {
        $return = $this->argv;
        
        $id = array_search('-remote', $return);
        unset($return[$id]);
        unset($return[$id + 1]);
        unset($return[0]);
        return json_encode(array_values($return));
    }

    public function toIni() {
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

    public function toYaml() {
        $yaml = array('phpversion'          => $this->options['phpversion'],
                      'ignore_dirs'         => $this->options['ignore_dirs'],
                      'include_dirs'        => $this->options['include_dirs'],
                      'file_extensions'     => $this->options['file_extensions'],
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

    public function duplicate($options) {
        $return = clone $this;
        
        // Only update existing values : ignoring the rest
        foreach($options as $key => $value) {
            if (isset($return->options[$key])) {
                $return->options[$key] = $value;
            }
        }

        return $return;
    }
}

?>