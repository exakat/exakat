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

class CommandLine extends Config {
    private $booleanOptions = array(
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
                                 '-svn'       => 'svn',
                                 '-bzr'       => 'bzr',
                                 '-hg'        => 'hg',
                                 '-composer'  => 'composer',
                                 '-copy'      => 'copy',    // Copy the local dir
                                 '-symlink'   => 'symlink', // make a symlink
                                 '-tgz'       => 'tgz',
                                 '-tbz'       => 'tbz',
                                 '-zip'       => 'zip',
                                 '-git'       => 'git',
                                 );

    private $valueOptions   = array('-f'            => 'filename',
                                    '-d'            => 'dirname',
                                    '-p'            => 'project',
                                    '-P'            => 'program',
                                    '-R'            => 'repository',
                                    '-T'            => 'thema',
                                    '-report'       => 'report',
                                    '-format'       => 'format',
                                    '-file'         => 'file',
                                    '-style'        => 'style',
                                    '-token_limit'  => 'token_limit',
                                    '-branch'       => 'branch',
                                    '-tag'          => 'tag',
                                    '-remote'       => 'remote',

                                    // This one is finally an array
                                    '-c'            => 'configuration',

//                                '-loader'       => 'Neo4jImport',
                                 );

    private $commands = array('analyze'       => 1,
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
                              'api'           => 1,
                              'jobqueue'      => 1,
                              'queue'         => 1,
                              'load'          => 1,
                              'project'       => 1,
                              'melis'         => 1,
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
                              'fetch'         => 1,
                              'proxy'         => 1,
                              'config'        => 1,
                              );

    public function __construct() {
        
    }

    public function loadConfig($args = array()) {
        if (empty($args)) {
            return false;
        }

        $vcsList = array('git', 'svn', 'bzr', 'hg', 'composer', 'tgz', 'tbz', 'zip');
        foreach($this->booleanOptions as $key => $config) {
            $id = array_search($key, $args);
            if ($id !== false) {
                // git is default, so it should be unset if another is set
                if (in_array($config, $vcsList)) {
                    foreach($vcsList as $vcs) {
                        $this->config[$vcs] = false;
                    }
                }
                $this->config[$config] = true;

                unset($args[$id]);
            }
        }

        foreach($this->valueOptions as $key => $config) {
            while( ($id = array_search($key, $args)) !== false ) {
                if (isset($args[$id + 1])) {
                    if (is_string($args[$id + 1]) && isset($this->valueOptions[$args[$id + 1]])) {
                        // in case this option value is actually the next option (exakat -p -T)
                        // We just ignore it
                        unset($args[$id]);
                        continue;
                    } 
                    
                    // Normal case is here
                    switch ($config) {
                        case 'program' :
                            if (!isset($this->config['program'])) {
                                $this->config['program'] = $args[$id + 1];
                            } elseif (is_string($this->config['program'])) {
                                $this->config['program'] = array($this->config['program'], 
                                                                 $args[$id + 1],
                                                                );
                            } else {
                                $this->config['program'][] = $args[$id + 1];
                            }
                            break;

                        case 'configuration' :
                            if (empty($this->config['configuration'])) {
                                $this->config['configuration'] = array();
                            } 
                            if (strpos($args[$id + 1], '=') === false) {
                                $name = trim($args[$id + 1]);
                                $value = '';
                            } else {
                                list($name, $value) = explode('=', trim($args[$id + 1]));
                            }
                            if (in_array($name, array('ignore_dirs', 'include_dirs', 'file_extensions'))) {
                                if (!isset($this->config['configuration'][$name])) {
                                    $this->config['configuration'][$name] = array();
                                }
                                $this->config['configuration'][$name][] = $value;
                            } else {
                                $this->config['configuration'][$name] = $value;
                            }
                            break;

                        default : 
                             $this->config[$config] = $args[$id + 1];
                    }

                    unset($args[$id]);
                    unset($args[$id + 1]);
                }
            }
        }
        
        $command = array_shift($args);
        if (isset($command, $this->commands[$command])) {
            $this->config['command'] = $command;
        }

        if (!empty($args)) {
            $c = count($args);
            if (isset($this->config['verbose'])) {
                display( 'Found '.$c.' argument'.($c > 1 ? 's' : '').' that '.($c > 1 ? 'are' : 'is')." not understood.\n\n\"".implode('", "', $args)."\"\n\nIgnoring ".($c > 1 ? 'them all' : 'it'.".\n"));
            }
        }

        // Special case for onepage command. It will only work on 'onepage' project
        if (isset($this->config['command']) && 
            $this->config['command'] == 'onepage') {

            $this->config['project']   = 'onepage';
            $this->config['thema']     = 'OneFile';
            
            $this->config['format']    = 'OnepageJson';
            $this->config['file']      = str_replace('/code/', '/reports/', substr($this->config['filename'], 0, -4));
            $this->config['quiet']     = true;
            $this->config['norefresh'] = true;
        }

        // Special case for onepage command. It will only work on 'onepage' project
        if (isset($this->config['command']) && 
            $this->config['command'] == 'codacy') {
            $this->config['thema']     = 'Codacy';
            $this->config['format']    = 'Codacy';
            $this->config['quiet']     = true;
            $this->config['norefresh'] = true;

            $this->config['project']   = 'codacy';
        }
        
        return true;
    }
}

?>