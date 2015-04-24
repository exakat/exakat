<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


class Config {
    static private $singleton      = null;
           private $configFile     = array();
           private $commandline    = array();
           private $argv           = array();
           public  $dir_root       = '.';
           public  $projects_root  = '.';
           public  $is_phar        = true;
           public  $executable     = '';
           private $projectConfig  = array();
        
           private $options = array();
     
    private function __construct($argv) {
        $this->argv = $argv;
        
        $this->is_phar  = strpos(basename(dirname(__DIR__)), '.phar') !== false;
        if ($this->is_phar) {
            $this->projects_root = substr(dirname(dirname(__DIR__)), 7);
            $this->executable    = $_SERVER['SCRIPT_NAME'];
            $this->dir_root      = 'phar://'.$this->executable;
        } else {
            $this->executable    = $_SERVER['SCRIPT_NAME'];
            $this->dir_root      = dirname(__DIR__);
            $this->projects_root = dirname(__DIR__);
        }
        
        $configFile = $this->projects_root.'/config/config.ini'; 
        if (file_exists($this->projects_root.'/config/config.ini')) {
            $this->configFile = parse_ini_file($configFile);
        } else {
            $configFile = $this->projects_root.'/config/config-default.ini'; 
            if (file_exists($configFile)) {
                $this->configFile = parse_ini_file($configFile);
            } else {
                $this->configFile = array();
            }
        }

        // then read the config from the commandline (if any)
        $this->readCommandline();
        
        // then read the config for the project in its folder
        if (isset($this->commandline['project'])) {
            $this->readProjectConfig($this->commandline['project']);
        } 
        
        // build the actual config. Project overwrite commandline overwrites config, if any.
        $this->options = array_merge($this->configFile, $this->commandline, $this->projectConfig);

        if ($this->options['neo4j_folder'][0] != '/') {
            $this->options['neo4j_folder'] = dirname($this->projects_root).'/'.$this->options['neo4j_folder'];
        }
    }
    
    static public function factory($argv = array()) {
        if (empty($argv)) {
            if (empty(self::$singleton)) {
                self::$singleton = new self(array());
            }
            return self::$singleton;
        } else {
            if (is_object($argv) && ($argv instanceof \Config)) {
                self::$singleton = $argv;
            } else {
                self::$singleton = new self($argv);
            }
            return self::$singleton;
        }
        
    }

    static public function factorySingle($argv = array()) {
        return new Config($argv);
    }

    public function __isset($name) {
        return isset($this->options[$name]);
    }
    
    public function __get($name) {
        if (isset($this->options[$name])) {
            $return = $this->options[$name];
        } else {
            $return = null;
        }
        
        return $return;
    }
    
    public function __set($name, $value) {
        print "It is not possible to modify configuration $name with value '$value'\n";
    }

    private function readProjectConfig($project) {
        if (!file_exists($this->projects_root.'/projects/'.$project.'/config.ini')) {
            return null;
        }
        
        $this->projectConfig = parse_ini_file($this->projects_root.'/projects/'.$project.'/config.ini');
        
        foreach($this->projectConfig as &$value) {
            if (is_array($value) && empty($value[0])) {
                unset($value[0]);
            }
        }
        unset($value);
        
        $other_php_versions = array();
        foreach(array('52', '53', '54', '55', '56', '70') as $version) {
            $php = new \Phpexec($version[0].'.'.$version[1]);
            if ($php->isValid()) {
                $other_php_versions[] = $version;
            }
        }
        
        // check and default values
        $defaults = array( 'ignore_dirs'        => array('tests', 'test', 'Tests'),
                           'other_php_versions' => $other_php_versions,
                           'phpversion'         => PHP_VERSION
                           );
        
        foreach($defaults as $name => $value) {
            if (!isset($this->projectConfig[$name])) {
                $this->projectConfig[$name] = $value;
            }
        }
        
        if (is_string($this->projectConfig['other_php_versions'])) {
            $this->projectConfig['other_php_versions'] = explode(',', $this->projectConfig['other_php_versions']);
            foreach($this->projectConfig['other_php_versions'] as &$version) {
                $version = str_replace('.', '', trim($version));
            }
        }
        
        return null;
    }

    private function readCommandline() {
        $args = $this->argv;
        unset($args[0]);

        if (empty($args)) {
            return null;
        }
        
        $optionsBoolean = array(
                                 '-v'         => array('verbose',        false),
                                 '-Q'         => array('quick',          false),
                                 '-h'         => array('help',           false),
                                 '-r'         => array('recursive',      false),
                                 '-u'         => array('update',         false),
                                 '-D'         => array('delete',         false),
                                 '-l'         => array('lint',           false),
                                 '-json'      => array('json',           false),
                                 '-dot'       => array('dot',            false),
                                 '-ss'        => array('ss',             false),
                                 '-sm'        => array('sm',             false),
                                 '-sl'        => array('sl',             false),
                                 '-nodep'     => array('noDependencies', false),
                                 '-norefresh' => array('noRefresh',      false),
                                 '-today'     => array('today',          false),
                                 '-none'      => array('none',           false),
                                 '-table'     => array('table',          false),
                                 '-text'      => array('text',           false),
                                 '-o'         => array('output',         false),
                                 );

        foreach($optionsBoolean as $key => $config) {
            if (($id = array_search($key, $args)) !== false) {
                $this->commandline[$config[0]] = true;

                unset($args[$id]);
            } else {
                $this->commandline[$config[0]] = $config[1];
            }
        }
                                 
        $optionsValue   = array('-f'            => array('filename',    null),
                                '-d'            => array('dirname',     null),
                                '-p'            => array('project',     'default'),
                                '-P'            => array('program',     null),
                                '-R'            => array('repository',  false),
                                '-T'            => array('thema',       null),
                                '-report'       => array('report',      'Premier'),
                                '-format'       => array('format',      'Text'),
                                '-file'         => array('file',        'report'),
                                '-style'        => array('style',       'ALL'), 
                                '-neo4j_host'   => array('neo4j_host',  '127.0.0.1'), 
                                '-neo4j_port'   => array('neo4j_port',  '7474'), 
                                '-neo4j_folder' => array('neo4j_folder',  $this->projects_root.'/neo4j'), 
                                 );

        foreach($optionsValue as $key => $config) {
            if ( ($id = array_search($key, $args)) !== false) {
                $this->commandline[$config[0]] = $args[$id + 1];

                unset($args[$id]);
                unset($args[$id + 1]);
            } else {
                $this->commandline[$config[0]] = $config[1];
            }
        }

        $commands = array('analyze'     => 1, 
                          'build_root'  => 1, 
                          'constantes'  => 1, 
                          'clean'       => 1, 
                          'cleandb'     => 1, 
                          'doctor'      => 1, 
                          'errors'      => 1,
                          'export'      => 1,
                          'files'       => 1, 
                          'help'        => 1, 
                          'init'        => 1, 
                          'load'        => 1, 
                          'log2csv'     => 1, 
                          'magicnumber' => 1, 
                          'project'     => 1, 
                          'projectspip' => 1, 
                          'phploc'      => 1, 
                          'report_all'  => 1,
                          'report'      => 1, 
                          'results'     => 1, 
                          'stat'        => 1, 
                          'status'      => 1, 
                          'tokenizer'   => 1, 
                          'version'     => 1,
                          'onepage'     => 1,
                          );

        if (count($args) > 0) {
            $arg = array_shift($args);
            if (isset($commands[$arg])) {
                $this->commandline['command'] = $arg;
            } else {
                array_unshift($args, $arg);
                $this->commandline['command'] = 'version';
            }
        }

        if (count($args) != 0) {
            $c = count($args);
            print "Found ".$c." argument".($c > 1 ? "s" : '')." that ".($c > 1 ? "are" : 'is')." not understood.\n\n\"".implode('", "', $args)."\"\n\nIgnoring ".($c > 1 ? "them all" : 'it').".\n";
        }
    }
}

?>
