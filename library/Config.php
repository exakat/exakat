<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
           public  $codePath       = '.';
           public  $is_phar        = true;
           public  $executable     = '';
           private $projectConfig  = array();
        
           private $options = array();

           private $defaultConfig  = array( // directives with boolean value
                                            'verbose'        => false,
                                            'quick'          => false,
                                            'quiet'          => false,
                                            'help'           => false,
                                            'recursive'      => false,
                                            'update'         => false,
                                            'delete'         => false,
                                            'lint'           => false,
                                            'json'           => false,
                                            'dot'            => false,
                                            'ss'             => false,
                                            'sm'             => false,
                                            'sl'             => false,
                                            'noDependencies' => false,
                                            'noRefresh'      => false,
                                            'today'          => false,
                                            'none'           => false,
                                            'table'          => false,
                                            'text'           => false,
                                            'output'         => false,
                                            
                                            'git'            => true,
                                            'svn'            => false,
                                            'hg'             => false,
                                            'composer'       => false,
                                            'tgz'            => false,
                                            'tbz'            => false,
                                            'zip'            => false,

                                             // directives with literal value
                                            'filename'       => null,
                                            'dirname'        => null,
                                            'project'        => 'default',
                                            'program'        => null,
                                            'repository'     => false,
                                            'thema'          => null,
                                            'report'         => 'Premier',
                                            'format'         => 'Text',
                                            'file'           => 'stdout',
                                            'style'          => 'ALL',
                                            'neo4j_host'     => '127.0.0.1',
                                            'neo4j_port'     => '7474',
                                           );

        private static $BOOLEAN_OPTIONS = array(
                                 '-v'         => 'verbose',
                                 '-Q'         => 'quick',
                                 '-q'         => 'quiet',
                                 '-h'         => 'help',
                                 '-r'         => 'recursive',
                                 '-u'         => 'update',
                                 '-D'         => 'delete',
                                 '-l'         => 'lint',
                                 '-json'      => 'json',
                                 '-dot'       => 'dot',
                                 '-ss'        => 'ss',
                                 '-sm'        => 'sm',
                                 '-sl'        => 'sl',
                                 '-nodep'     => 'noDependencies',
                                 '-norefresh' => 'noRefresh',
                                 '-today'     => 'today',
                                 '-none'      => 'none',
                                 '-table'     => 'table',
                                 '-text'      => 'text',
                                 '-o'         => 'output',

                                 '-git'       => 'git',
                                 '-svn'       => 'svn',
                                 '-hg'        => 'hg',
                                 '-composer'  => 'composer',
                                 '-tgz'       => 'tgz',
                                 '-tbz'       => 'tbz',
                                 '-zip'       => 'zip',
                                 );

        private static $COMMANDS = array('analyze'       => 1, 
                                         'build_root'    => 1, 
                                         'constantes'    => 1, 
                                         'clean'         => 1, 
                                         'cleandb'       => 1, 
                                         'dump'          => 1, 
                                         'doctor'        => 1, 
                                         'errors'        => 1,
                                         'export'        => 1,
                                         'files'         => 1, 
                                         'findextlib'    => 1,
                                         'help'          => 1, 
                                         'init'          => 1, 
                                         'jobqueue'      => 1, 
                                         'queue'         => 1, 
                                         'load'          => 1, 
                                         'log2csv'       => 1, 
                                         'magicnumber'   => 1, 
                                         'project'       => 1, 
                                         'projectspip'   => 1, 
                                         'phploc'        => 1, 
                                         'report_all'    => 1,
                                         'report'        => 1, 
                                         'report2'       => 1, 
                                         'results'       => 1, 
                                         'stat'          => 1, 
                                         'status'        => 1, 
                                         'tokenizer'     => 1, 
                                         'version'       => 1,
                                         'onepage'       => 1,
                                         'onepagereport' => 1,
                                         'vector'        => 1,
                                         'classes'       => 1,
                                         'test'          => 1,
                                         'update'        => 1,
                                         );
                               
    static private $stack = array();
     
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
            $this->codePath = realpath($this->projects_root.'/projects/'.$this->commandline['project'].'/code');
        }  else {
            $this->codePath = '/No/Path/To/Code';
        }
                
        // build the actual config. Project overwrite commandline overwrites config, if any.
        $this->options = array_merge($this->defaultConfig, $this->configFile, $this->commandline, $this->projectConfig);

        if ($this->options['neo4j_folder'][0] !== '/') {
            $this->options['neo4j_folder'] = $this->projects_root.'/'.$this->options['neo4j_folder'];
        }
        $this->options['neo4j_folder'] = realpath($this->options['neo4j_folder']);
    }
    
    static public function factory($argv = array()) {
        if (empty($argv)) {
            if (empty(static::$singleton)) {
                self::$singleton = new self(array());
                self::$stack[] = self::$singleton;
            }
            return static::$singleton;
        } else {
            if (is_object($argv) && ($argv instanceof \Config)) {
                self::$singleton = $argv;
            } else {
                self::$singleton = new self($argv);
            }
            self::$stack[] = self::$singleton;
            return self::$singleton;
        }
    }

    static public function factorySingle($argv = array()) {
        return new Config($argv);
    }
    
    static public function push($argv = array()) {
        self::factory($argv);
        
        return self::$singleton;
    }

    static public function pop() {
        $r = array_pop(self::$stack);
        self::$singleton = self::$stack[count(self::$stack) -1];
        
        return $r;
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
        display("It is not possible to modify configuration $name with value '$value'\n");
    }

    private function readProjectConfig($project) {
        if (!file_exists($this->projects_root.'/projects/'.$project.'/config.ini')) {
            return null;
        }
        
        $this->projectConfig = parse_ini_file($this->projects_root.'/projects/'.$project.'/config.ini');
        
        // removing empty values in the INI file
        foreach($this->projectConfig as &$value) {
            if (is_array($value) && empty($value[0])) {
                unset($value[0]);
            }
        }
        unset($value);
        
        $other_php_versions = array();
        foreach(array('52', '53', '54', '55', '56', '70', '71') as $version) {
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
            unset($version);
        }
    }

    private function readCommandline() {
        $args = $this->argv;
        unset($args[0]);
        
        if (empty($args)) {
            return array();
        }
        
        foreach(static::$BOOLEAN_OPTIONS as $key => $config) {
            $id = array_search($key, $args);
            if ($id !== false) {
                $this->commandline[$config] = true;

                unset($args[$id]);
            } 
        }
        
        // git is default, so it should be unset if another is set
        $this->commandline['git'] = (boolean) (true ^ ((isset($this->commandline['svn'])       && $this->commandline['svn'])      || 
                                                       (isset($this->commandline['hg'])        && $this->commandline['hg'])       || 
                                                       (isset($this->commandline['hg'])        && $this->commandline['hg'])       || 
                                                       (isset($this->commandline['composer'])  && $this->commandline['composer']) || 
                                                       (isset($this->commandline['tgz'])       && $this->commandline['tgz'])      || 
                                                       (isset($this->commandline['tbz'])       && $this->commandline['tbz'])      || 
                                                       (isset($this->commandline['zip'])       && $this->commandline['zip']))      );

        $optionsValue   = array('-f'            => 'filename',
                                '-d'            => 'dirname',
                                '-p'            => 'project',
                                '-P'            => 'program',
                                '-R'            => 'repository',
                                '-T'            => 'thema',
                                '-report'       => 'report',
                                '-format'       => 'format',
                                '-file'         => 'file',
                                '-style'        => 'style', 
                                '-neo4j_host'   => 'neo4j_host', 
                                '-neo4j_port'   => 'neo4j_port', 
                                '-neo4j_folder' => 'neo4j_folder', 
                                '-token_limit'  => 1000000,
                                 );

        foreach($optionsValue as $key => $config) {
            $id = array_search($key, $args);
            if ( $id !== false) {
                if (isset($args[$id + 1])) {
                    if (isset($optionsValue[$args[$id + 1]])) {
                        // in case this option value is actually the next option (exakat -p -T)
                        // We just ignore it
                        unset($args[$id]);
                    } else {
                        // Normal case is here
                        $this->commandline[$config] = $args[$id + 1];

                        unset($args[$id]);
                        unset($args[$id + 1]);
                    }
                }
            } 
        }

        if (count($args) > 0) {
            $arg = array_shift($args);
            if (null !== @static::$COMMANDS[$arg]) {
                $this->commandline['command'] = $arg;
            } else {
                array_unshift($args, $arg);
                $this->commandline['command'] = 'version';
            }
        }

        if (count($args) != 0) {
            $c = count($args);
            display( 'Found '.$c.' argument'. $c > 1 ? 's' : '' .' that '.$c > 1 ? 'are' : 'is' ." not understood.\n\n\"".implode('", "', $args)."\"\n\nIgnoring ". $c > 1 ? 'them all' : 'it'. ".\n");
        }

        // Special case for onepage command. It will only work on 'onepage' project
        if ($this->commandline['command'] == 'onepage') {
            $this->commandline['project']   = 'onepage';
            $this->commandline['thema']     = 'OneFile';
            $this->commandline['format']    = 'Json';
            $this->commandline['file']      = 'onepage';
            $this->commandline['norefresh'] = true;
            
        }
    }
}

?>
