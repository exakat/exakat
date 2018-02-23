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
                                '-neo4j_host'   => 'neo4j_host',
                                '-neo4j_port'   => 'neo4j_port',
                                '-neo4j_folder' => 'neo4j_folder',
                                '-token_limit'  => 'token_limit',
                                '-branch'       => 'branch',
                                '-tag'          => 'tag',
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
                              );

    public function __construct() {
        
    }

    public function loadConfig($args = array()) {
        if (empty($args)) {
            return false;
        }

        foreach($this->booleanOptions as $key => $config) {
            $id = array_search($key, $args);
            if ($id !== false) {
                $this->config[$config] = true;

                unset($args[$id]);
            }
        }

        // git is default, so it should be unset if another is set
        /*
        $this->config['git'] = (boolean) (true ^ ((isset($this->config['svn'])       && $this->config['svn'])      ||
                                                  (isset($this->config['hg'])        && $this->config['hg'])       ||
                                                  (isset($this->config['bzr'])       && $this->config['bzr'])      ||
                                                  (isset($this->config['composer'])  && $this->config['composer']) ||
                                                  (isset($this->config['tgz'])       && $this->config['tgz'])      ||
                                                  (isset($this->config['tbz'])       && $this->config['tbz'])      ||
                                                  (isset($this->config['zip'])       && $this->config['zip'])      ||
                                                  (isset($this->config['copy'])      && $this->config['copy'])     ||
                                                  (isset($this->config['symlink'])   && $this->config['symlink']))    );
                                                  */

        foreach($this->valueOptions as $key => $config) {
            while( ($id = array_search($key, $args)) !== false ) {
                if (isset($args[$id + 1])) {
                    if (is_string($args[$id + 1]) && isset($optionsValue[$args[$id + 1]])) {
                        // in case this option value is actually the next option (exakat -p -T)
                        // We just ignore it
                        unset($args[$id]);
                    } else {
                        // Normal case is here
                        if ($config === 'program') {
                            if (!isset($this->config['program'])) {
                                $this->config['program'] = $args[$id + 1];
                            } elseif (is_string($this->config['program'])) {
                                $this->config['program'] = array($this->config['program'], 
                                                                 $args[$id + 1],
                                                                );
                            } else {
                                $this->config['program'][] = $args[$id + 1];
                            }
                        } else {
                            $this->config[$config] = $args[$id + 1];
                        }

                        unset($args[$id]);
                        unset($args[$id + 1]);
                    }
                }
            }
        }

        if (isset($args[1], $this->commands[$args[1]])) {
            $this->config['command'] = $args[1];
            unset($args[1]);
        }

        if (!empty($args) != 0) {
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
        
        return true;
    }
}

?>