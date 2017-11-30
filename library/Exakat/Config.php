<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

use Exakat\Config;
use Exakat\Phpexec;
use Exakat\Reports\Reports;
use Exakat\Exceptions\InaptPHPBinary;
use Phar;

class Config {
    static private $singleton      = null;
    private $configFile            = array();
    private $commandline           = array();
    private $argv                  = array();
    public  $dir_root              = '.';
    public  $projects_root         = '.';
    public  $codePath              = '.';
    public  $is_phar               = true;
    public  $executable            = '';
    private $projectConfig         = array();
    private $codacyConfig          = array();

    private $options = array('configFiles' => array());

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
                                            'array'          => false,
                                            'dot'            => false,
                                            'noDependencies' => false,
                                            'noRefresh'      => false,
                                            'today'          => false,
                                            'none'           => false,
                                            'table'          => false,
                                            'text'           => false,
                                            'output'         => false,

                                            'git'            => true,
                                            'svn'            => false,
                                            'bzr'            => false,
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
                                            'analyzers'      => array(), 
                                            'report'         => 'Premier',
                                            'format'         => 'Text',
                                            'file'           =>  Reports::STDOUT,
                                            'style'          => 'ALL',

                                            'neo4j_host'     => '127.0.0.1',
                                            'neo4j_port'     => '7474',
                                            'neo4j_folder'   => 'neo4j',
                                            'neo4j_login'    => 'admin',
                                            'neo4j_password' => 'admin',

                                            'gsneo4j_host'   => '127.0.0.1',
                                            'gsneo4j_port'   => '7474',
                                            'gsneo4j_folder' => 'tinkergraph',
                                            
                                            'tinkergraph_host'   => '127.0.0.1',
                                            'tinkergraph_port'   => '7474',
                                            'tinkergraph_folder' => 'tinkergraph',

                                            'branch'         => 'master',
                                            'tag'            => '',

                                            'php'           => '',
                                            'php52'         => '',
                                            'php53'         => '',
                                            'php54'         => '',
                                            'php55'         => '',
                                            'php56'         => '',
                                            'php70'         => '',
                                            'php71'         => '',
                                            'php72'         => '',
                                            'php73'         => '',

                                            'phpversion'    => '7.1',
                                            'token_limit'   => '1000000',

                                            'command'       => 'version',

                                            'ignore_dirs'         => array(),
                                            'include_dirs'        => array(),
                                            'file_extensions'     => array(),
                                            'project_name'        => '',
                                            'project_url'         => '',
                                            'project_vcs'         => '',
                                            'project_description' => '',
                                            'project_packagist'   => '',
                                            'other_php_versions'  => '',

                                            'project_reports'     => array('Ambassador'),
                                            'project_themes'      => array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 
                                                                           'CompatibilityPHP70', 'CompatibilityPHP71', 'CompatibilityPHP72', 'CompatibilityPHP73',
                                                                           'Dead code', 'Security', 'Analyze', 'Preferences',
                                                                           'Appinfo', 'Appcontent'),
                                            
                                           );

    private $GREMLINS = array( 'neo4j'       => 'Gremlin3',
                               'tinkergraph' => 'Tinkergraph',
                               'gsneo4j'     => 'GSNeo4j',
                               'janusgraph'  => 'Janusgraph',
                               'januscaes'   => 'JanusCaES',
                               'nogremlin'   => 'NoGremlin',
                               'tcsv'        => 'Tcsv',
                               );

    private $LOADERS = array( 'neo4j'       => 'Neo4jImport', // Could be Neo4jImport, CypherG3
                              'tinkergraph' => 'Tinkergraph',
                              'gsneo4j'     => 'GSNeo4j',
                              'janusgraph'  => 'Janusgraph',
                              'januscaes'   => 'JanusCaES',
                              'nogremlin'   => 'NoLoader',
                              'tcsv'        => 'Tcsv',
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
                                 '-array'     => 'array',
                                 '-dot'       => 'dot',

                                 '-nodep'     => 'noDependencies',
                                 '-norefresh' => 'noRefresh',
                                 '-none'      => 'none',
                                 '-text'      => 'text',
                                 '-o'         => 'output',
                                 '-stop'      => 'stop',
                                 '-ping'      => 'ping',
                                 '-restart'   => 'restart',
                                 '-start'     => 'start',
                                 '-collect'   => 'collect',

    // Vcs
                                 '-git'       => 'git',
                                 '-svn'       => 'svn',
                                 '-bzr'       => 'bzr',
                                 '-hg'        => 'hg',
                                 '-composer'  => 'composer',
                                 '-copy'      => 'copy',    // Copy the local dir
                                 '-symlink'   => 'symlink', // make a symlink

    // Archive formats
                                 '-tgz'       => 'tgz',
                                 '-tbz'       => 'tbz',
                                 '-zip'       => 'zip',
                                 );

    private static $COMMANDS = array('analyze'       => 1,
                                     'anonymize'     => 1,
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
                                     'catalog'       => 1,
                                     'remove'        => 1,
                                     'server'        => 1,
                                     'jobqueue'      => 1,
                                     'queue'         => 1,
                                     'load'          => 1,
                                     'project'       => 1,
                                     'codacy'        => 1,
                                     'report'        => 1,
                                     'results'       => 1,
                                     'stat'          => 1,
                                     'status'        => 1,
                                     'version'       => 1,
                                     'onepage'       => 1,
                                     'onepagereport' => 1,
                                     'test'          => 1,
                                     'update'        => 1,
                                     'upgrade'       => 1,
                                     );

    static private $stack = array();

    public function __construct($argv) {
        $this->argv = $argv;

        $pharRunning = Phar::Running();
        $this->is_phar  = !empty($pharRunning);
        if ($this->is_phar) {
            $this->executable    = $_SERVER['SCRIPT_NAME'];
            $this->projects_root = getcwd();
            $this->dir_root      = 'phar://'.$this->executable;

            assert_options(ASSERT_ACTIVE, 0);

            error_reporting(0);
            ini_set('display_errors', 0);
        } else {
            $this->executable    = $_SERVER['SCRIPT_NAME'];
            $this->dir_root      = dirname(dirname(__DIR__));
            $this->projects_root = getcwd();

            assert_options(ASSERT_ACTIVE, 1);
            assert_options(ASSERT_BAIL, 1);

            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }

        $inis = array();
        $configFiles = array('/etc/exakat.ini',
                             '/etc/exakat/exakat.ini',
                             $this->projects_root.'/config/exakat.ini'
                             );
        foreach($configFiles as $id => $configFile) {
            if (file_exists($configFile)) {
                $inis[] = parse_ini_file($configFile);
                $this->options['configFiles'][] = $configFile;
            } 
        }

        $this->configFile = empty($inis) ? array() : call_user_func_array('array_merge', $inis);
        if (empty($this->configFile['php'])) {
            $this->configFile['php'] = !isset($_SERVER['_']) ? $_SERVER['_'] : '/usr/bin/env php ';
        }
        if (empty($this->configFile['graphdb']) ||
            !in_array($this->configFile['graphdb'], array_keys($this->GREMLINS)) ) {
            // stick to legacy names
            $this->configFile['graphdb'] = 'gsneo4j';
        }
        $this->configFile['gremlin'] = $this->GREMLINS[$this->configFile['graphdb']];
        $this->configFile['loader']  = $this->LOADERS[$this->configFile['graphdb']];

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
        $this->options = array_merge($this->options, $this->defaultConfig, $this->configFile, $this->projectConfig, $this->codacyConfig, $this->commandline);

        $graphdb = $this->options['graphdb'];
        if (isset($this->options[$graphdb.'_folder']) && 
            $this->options[$graphdb.'_folder'][0] !== '/') {
            $this->options[$graphdb.'_folder'] = $this->projects_root.'/'.$this->options[$graphdb.'_folder'];
        }
        $this->options[$graphdb.'_folder'] = realpath($this->options[$graphdb.'_folder']);
        
        $this->options['php'] = $_SERVER['_'];
        if ($this->options['command'] !== 'doctor') {
            $this->checkSelf();
        }
        
        if (empty(self::$singleton)){
            self::$singleton = $this;
            self::$stack[] = self::$singleton;
        }
        
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

    public function isProject($name) {
        return isset($this->projectConfig[$name]);
    }

    public function __set($name, $value) {
        display("It is not possible to modify configuration $name with value '$value'\n");
    }

    private function readProjectConfig($project) {
        if (!file_exists($this->projects_root.'/projects/'.$project.'/config.ini')) {
            $this->projectConfig = array();
        } else {
            $this->projectConfig = parse_ini_file($this->projects_root.'/projects/'.$project.'/config.ini');
            if (file_exists($this->projects_root.'/projects/'.$project.'/config.cache')) {
                $this->projectConfig = array_merge($this->projectConfig,
                                                   parse_ini_file($this->projects_root.'/projects/'.$project.'/config.cache'));
            }
        }

        // removing empty values in the INI file
        foreach($this->projectConfig as &$value) {
            if (is_array($value) && empty($value[0])) {
                unset($value[0]);
            }
        }
        unset($value);

        $other_php_versions = array();
        foreach(array('52', '53', '54', '55', '56', '70', '71', '72', '73') as $version) {
            if (empty($this->configFile['php'.$version])) {
                continue;
            }
            $php = new Phpexec($version[0].'.'.$version[1], $this->configFile['php'.$version]);
            if ($php->isValid()) {
                $other_php_versions[] = $version;
            }
        }
    
        // check and default values
        $defaults = array( 'ignore_dirs'        => array('/test', '/tests', '/Tests', '/Test', '/example', '/examples', '/docs', '/doc', '/tmp', '/version', '/vendor', '/js', '/lang', '/data', '/css', '/cache', '/vendor', '/assets', '/spec', '/sql'),
                           'other_php_versions' => $other_php_versions,
                           'phpversion'         => substr(PHP_VERSION, 0, 3),
                           'file_extensions'    => array('php', 'php3', 'inc', 'tpl', 'phtml', 'tmpl', 'phps', 'ctp'),
//                           'loader'             => 'Neo4jImport',
                           'project_themes'     => 'CompatibilityPHP53,CompatibilityPHP54,CompatibilityPHP55,CompatibilityPHP56,CompatibilityPHP70,CompatibilityPHP71,CompatibilityPHP72,CompatibilityPHP73,Dead code,Security,Analyze,Preferences,Appinfo,Appcontent',
                           'project_reports'    => array('Ambassador'),
                        );

        foreach($defaults as $name => $value) {
            if (empty($this->projectConfig[$name])) {
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

        if (is_string($this->projectConfig['file_extensions'])) {
            $this->projectConfig['file_extensions'] = explode(',', $this->projectConfig['file_extensions']);
            foreach($this->projectConfig['file_extensions'] as &$ext) {
                $ext = trim($ext, '. ');
            }
            unset($ext);
        }

        if (is_string($this->projectConfig['project_reports'])) {
            $this->projectConfig['project_reports'] = explode(',', $this->projectConfig['project_reports']);
            foreach($this->projectConfig['project_reports'] as &$ext) {
                $ext = trim($ext);
            }
            unset($ext);
        }

        if (is_string($this->projectConfig['project_themes'])) {
            $this->projectConfig['project_themes'] = explode(',', $this->projectConfig['project_themes']);
            foreach($this->projectConfig['project_themes'] as &$ext) {
                $ext = trim($ext);
            }
            unset($ext);
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
                                                       (isset($this->commandline['bzr'])       && $this->commandline['bzr'])      ||
                                                       (isset($this->commandline['composer'])  && $this->commandline['composer']) ||
                                                       (isset($this->commandline['tgz'])       && $this->commandline['tgz'])      ||
                                                       (isset($this->commandline['tbz'])       && $this->commandline['tbz'])      ||
                                                       (isset($this->commandline['zip'])       && $this->commandline['zip'])      ||
                                                       (isset($this->commandline['copy'])      && $this->commandline['copy'])     ||
                                                       (isset($this->commandline['symlink'])   && $this->commandline['symlink']))    );

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
                                '-token_limit'  => 'token_limit',
                                '-branch'       => 'branch',
                                '-tag'          => 'tag',
//                                '-loader'       => 'Neo4jImport',
                                 );

        foreach($optionsValue as $key => $config) {
            while( ($id = array_search($key, $args)) !== false ) {
                if (isset($args[$id + 1])) {
                    if (is_string($args[$id + 1]) && isset($optionsValue[$args[$id + 1]])) {
                        // in case this option value is actually the next option (exakat -p -T)
                        // We just ignore it
                        unset($args[$id]);
                    } else {
                        // Normal case is here
                        if ($config === 'program') {
                            if (!isset($this->commandline['program'])) {
                                $this->commandline['program'] = $args[$id + 1];
                            } elseif (is_string($this->commandline['program'])) {
                                $this->commandline['program'] = array($this->commandline['program'], 
                                                                      $args[$id + 1]);
                            } else {
                                $this->commandline['program'][] = $args[$id + 1];
                            }
                        } else {
                            $this->commandline[$config] = $args[$id + 1];
                        }

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
            if (isset($this->commandline['verbose'])) {
                print 'Found '.$c.' argument'.($c > 1 ? 's' : '').' that '.($c > 1 ? 'are' : 'is')." not understood.\n\n\"".implode('", "', $args)."\"\n\nIgnoring ".($c > 1 ? 'them all' : 'it'.".\n");
            }
        }

        if (!isset($this->commandline['command'])) {
            $this->commandline['command'] = 'help'; // Default behavior
        }

        // Special case for onepage command. It will only work on 'onepage' project
        if ($this->commandline['command'] == 'onepage') {
            $this->commandline['project']   = 'onepage';
            $this->commandline['thema']     = 'OneFile';
            $this->commandline['format']    = 'OnepageJson';
            $this->commandline['file']      = str_replace('/code/', '/reports/', substr($this->commandline['filename'], 0, -4));
            $this->commandline['quiet']     = true;
            $this->commandline['norefresh'] = true;
        }
    }
    
    private function checkSelf() {
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            throw new InaptPHPBinary('PHP needs to be version 7.0.0 or more to run exakat.('.PHP_VERSION.' provided)');
        }
        $extensions = array('curl', 'mbstring', 'sqlite3', 'hash', 'json');
        
        foreach($extensions as $extension) {
            if (!extension_loaded($extension)) {
                throw new InaptPHPBinary('PHP needs the '.$extension.' extension');
            }
        }
    }
}

?>