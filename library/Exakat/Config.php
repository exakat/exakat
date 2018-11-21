<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

use Exakat\Configsource\{CodacyConfig, CommandLine, DefaultConfig, DotExakatConfig, EmptyConfig, EnvConfig, ExakatConfig, ProjectConfig, RemoteConfig, ThemaConfig };
use Exakat\Exceptions\InaptPHPBinary;
use Exakat\Reports\Reports;
use Phar;

class Config {
    const PHP_VERSIONS = array('52', '53', '54', '55', '56', '70', '71', '72', '73', '74');

    public  $dir_root              = '.';
    public  $projects_root         = '.';
    public  $is_phar               = true;
    public  $executable            = '';
    public  $ext                   = null;

    private $projectConfig         = null;
    private $codacyConfig          = null;
    private $commandLineConfig     = null;
    private $defaultConfig         = null;
    private $exakatConfig          = null;
    private $dotExakatConfig       = null;
    private $envConfig             = null;
    private $argv                  = null;

    private $configFiles = array();
    private $options     = array();
    private $remotes     = array();
    private $themas      = array();
    
    public function __construct($argv) {
        $this->argv = $argv;

        $this->is_phar  = class_exists('\\Phar') && !empty(phar::running());
        if ($this->is_phar) {
            $this->executable    = $_SERVER['SCRIPT_NAME'];
            $this->projects_root = substr(dirname(phar::running()), 7);
            $this->dir_root      = phar::running();

            assert_options(ASSERT_ACTIVE, 0);

            error_reporting(0);
            ini_set('display_errors', 0);
            if (!file_exists("{$this->projects_root}/projects")) {
                mkdir("{$this->projects_root}/projects", 0755);
            }
            if (!file_exists("{$this->projects_root}/projects/.exakat")) {
                mkdir("{$this->projects_root}/projects/.exakat", 0755);
            }
            ini_set('error_log', "{$this->projects_root}/projects/.exakat/php.log");
        } else {
            $this->executable    = $_SERVER['SCRIPT_NAME'];
            $this->dir_root      = dirname(__DIR__, 2);
            // Run projects in the working directory
            if (dirname($_SERVER['SCRIPT_FILENAME']) === 'bin' &&
                dirname($_SERVER['SCRIPT_FILENAME'], 2) === 'vendor') {
                $this->projects_root = getcwd();
            } else {
                $this->projects_root = dirname(__DIR__, 2);
            }

            assert_options(ASSERT_ACTIVE, 1);
            assert_options(ASSERT_BAIL, 1);

            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
        
        unset($argv[0]);

        $this->defaultConfig = new DefaultConfig();

        $this->exakatConfig = new ExakatConfig($this->projects_root);
        if ($file = $this->exakatConfig->loadConfig(null)) {
            $this->configFiles[] = $file;
        }
        
        // then read the config from the commandline (if any)
        $this->commandLineConfig = new CommandLine();
        $this->commandLineConfig->loadConfig($argv);

        $this->envConfig = new EnvConfig($this->projects_root);
        if ($file = $this->envConfig->loadConfig(null)) {
            $this->configFiles[] = $file;
        }

        // then read the config for the project in its folder
        if ($this->commandLineConfig->get('project') === null) {
            $this->projectConfig   = new EmptyConfig();
            $this->dotExakatConfig = new EmptyConfig();
        } else {
            $this->projectConfig = new ProjectConfig($this->projects_root);
            if ($file = $this->projectConfig->loadConfig($this->commandLineConfig->get('project'))) {
                $this->configFiles[] = $file;
            }

            $this->dotExakatConfig = new DotExakatConfig($this->projects_root);
            if ($file = $this->dotExakatConfig->loadConfig($this->commandLineConfig->get('project'))) {
                $this->configFiles[] = $file;
            }
            $this->dotExakatConfig->loadConfig(null);
        }
        
        if ($this->commandLineConfig->get('command') === 'codacy') {
            $this->codacyConfig = new CodacyConfig($this->projects_root);
            if ($file = $this->codacyConfig->loadConfig($this->commandLineConfig->get('project'))) {
                $this->configFiles[] = $file;
            }
        } else {
            $this->codacyConfig    = new EmptyConfig();
        }

        // build the actual config. Project overwrite commandline overwrites config, if any.
        $this->options = array_merge($this->defaultConfig->toArray(), 
                                     $this->exakatConfig->toArray(), 
                                     $this->envConfig->toArray(),
                                     $this->projectConfig->toArray(), 
                                     $this->dotExakatConfig->toArray(), 
                                     $this->codacyConfig->toArray(), 
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

        if ($this->options['command'] !== 'doctor') {
            $this->checkSelf();
        }
        
        // autoload extensions
        $this->ext = new \AutoloadExt("{$this->dir_root}/ext");
        $this->ext->registerAutoload();
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
            throw new InaptPHPBinary('PHP needs to be version 7.0.0 or more to run exakat.('.PHP_VERSION.' provided)');
        }
        $extensions = array('curl', 'mbstring', 'sqlite3', 'hash', 'json');
        
        $missing = array();
        foreach($extensions as $extension) {
            if (!extension_loaded($extension)) {
                $missing[] = $extension;
            }
        }
        
        if (!empty($missing)) {
           throw new InaptPHPBinary('PHP needs '.(count($missing) == 1 ? 'one' : count($missing)).' extension'.(count($missing) > 1 ? 's' : '').' with the current version : '.implode(', ', $missing));
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
}

?>