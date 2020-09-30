<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy Ð Exakat SAS <contact(at)exakat.io>
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

use Exakat\Vcs\Vcs;
use Exakat\Tasks\Baseline;
use Exakat\Tasks\Extension;
use Exakat\Project;
use Exakat\Graph\Graph;

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

                                 '-debug'     => 'debug',

                                 '-nodep'     => 'noDependencies',
                                 '-norefresh' => 'noRefresh',
                                 '-text'      => 'text',
                                 '-o'         => 'output',
                                 '-stop'      => 'stop',
                                 '-ping'      => 'ping',
                                 '-restart'   => 'restart',
                                 '-start'     => 'start',
                                 '-collect'   => 'collect',
                                 '-load-dump' => 'load_dump', // for Dump

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
                                 '-rar'       => 'rar',
                                 '-7z'        => 'sevenz',
                                 '-git'       => 'git',
                                 '-cvs'       => 'cvs',
                                 '-none'      => 'none',
                                 );

    private $valueOptions   = array('-f'             => 'filename',
                                    '-d'             => 'dirname',
                                    '-p'             => 'project',
                                    '-P'             => 'program',
                                    '-R'             => 'repository',
                                    '-T'             => 'project_rulesets',
                                    '-report'        => 'report',
                                    '--report'       => 'report',
                                    '-format'        => 'format',
                                    '--format'       => 'format',
                                    '-file'          => 'file',
                                    '--file'         => 'file',
                                    '-style'         => 'style',
                                    '--style'        => 'style',
                                    '-token_limit'   => 'token_limit',
                                    '--token_limit'  => 'token_limit',
                                    '-branch'        => 'branch',
                                    '--branch'       => 'branch',
                                    '-tag'           => 'tag',
                                    '--tag'          => 'tag',
                                    '-remote'        => 'remote',
                                    '--remote'       => 'remote',
                                    '-graphdb'       => 'gremlin',
                                    '--graphdb'      => 'gremlin',
                                    '-version'       => 'version',
                                    '--version'      => 'version',

                                    '-baseline-set'  => 'baseline_set',
                                    '--baseline-set' => 'baseline_set',
                                    '-baseline-use'  => 'baseline_use',
                                    '--baseline-use' => 'baseline_use',

                                    // This one is finally an array
                                    '-c'            => 'configuration',
                                 );

    public static $commands = array('analyze'       => 1,
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
                                    'diff'          => 1,
                                    'project'       => 1,
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
                                    'config'        => 1,
                                    'show'          => 1,
                                    'baseline'      => 1,
                                    'install'       => 1,
                                    );

    public function __construct() {
        $this->config['command'] = '<no-command>';
        $this->config['project'] = new Project();  // Default to no object
    }
    
    public function setArgs(array $args = array()) : void {
        $this->args = $args;
    }

    public function loadConfig(Project $project) : ?string {
        if (empty($this->args)) {
            return self::NOT_LOADED;
        }

        $args = $this->args;
        // TODO : move this to VCS
        foreach($this->booleanOptions as $key => $config) {
            $id = array_search($key, $args);
            if ($id !== false) {
                // git is default, so it should be unset if another is set
                if (in_array($config, Vcs::SUPPORTED_VCS)) {
                    $this->config = $this->config + array_fill_keys(Vcs::SUPPORTED_VCS, false);
                }
                $this->config[$config] = true;

                unset($args[$id]);
            }
        }

        foreach($this->valueOptions as $key => $config) {
            while( ($id = array_search($key, $args)) !== false ) {
                if (!isset($args[$id + 1])) {
                    // case of a name, without a following name
                    // We just ignore it
                    unset($args[$id]);
                    continue;
                }

                if (is_string($args[$id + 1]) && isset($this->valueOptions[$args[$id + 1]])) {
                    // in case this option value is actually the next option (exakat -p -T)
                    // We just ignore it
                    unset($args[$id]);
                    continue;
                }

                // Normal case is here
                switch ($config) {
                    case 'project' :
                        if ($this->config['project']->isDefault()) {
                            $this->config['project'] = new Project($args[$id + 1]);
                        }
                        // Multiple -p are ignored : keep the first
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
                        if (in_array($name, array('ignore_dirs', 'include_dirs', 'file_extensions'), STRICT_COMPARISON)) {
                            if (!isset($this->config['configuration'][$name])) {
                                $this->config['configuration'][$name] = array();
                            }
                            $this->config['configuration'][$name][] = $value;
                        } else {
                            $this->config['configuration'][$name] = $value;
                        }
                        break;

                    case 'gremlin' :
                        $this->config['gremlin'] = $args[$id + 1];
                        if (!in_array($this->config['gremlin'], Graph::GRAPHDB, LOOSE_COMPARISON)) {
                            $this->config['gremlin'] = 'nogremlin';
                        }
                        break;

                    case 'format' :
                        if (isset($this->config['project_reports'])) {
                            $this->config['project_reports'][] = $args[$id + 1];
                        } else {
                            $this->config['project_reports'] = array($args[$id + 1]);
                        }
                        break;

                    case 'program' :
                        if (isset($this->config['project_rulesets'])) {
                            // program and project_rulesets are mutually exclusive
                            break;
                        } elseif (!isset($this->config['program'])) {
                            $this->config['program'] = $args[$id + 1];
                        } elseif (is_string($this->config['program'])) {
                            $this->config['program'] = array($this->config['program'],
                                                             $args[$id + 1],
                                                            );
                        } else {
                            $this->config['program'][] = $args[$id + 1];
                        }
                        break;

                    case 'project_rulesets' :
                        if (isset($this->config['program'])) {
                            // program and project_rulesets are mutually exclusive
                            break;
                        } elseif (isset($this->config['project_rulesets'])) {
                            $this->config['project_rulesets'][] = $args[$id + 1];
                        } else {
                            $this->config['project_rulesets'] = array($args[$id + 1]);
                        }
                        break;

                    default:
                        $this->config[$config] = $args[$id + 1];
                }

                unset($args[$id]);
                unset($args[$id + 1]);
            }
        }

        $command = array_shift($args);
        if (isset($command, self::$commands[$command])) {
            $this->config['command'] = $command;

            if ($this->config['command'] === 'extension') {
                $subcommand = array_shift($args);
                if (!in_array($subcommand, Extension::ACTIONS, STRICT_COMPARISON)) {
                    $subcommand = 'local';
                }
                $this->config['subcommand'] = $subcommand;

                if (in_array($subcommand, array('install', 'uninstall', 'update'), STRICT_COMPARISON)) {
                    $this->config['extension'] = array_shift($args);
                }
            } elseif ($this->config['command'] === 'baseline') {
                $subcommand = array_shift($args);
                if (!in_array($subcommand, Baseline::ACTIONS, STRICT_COMPARISON)) {
                    $subcommand = 'list';
                }
                $this->config['subcommand'] = $subcommand;

                if (in_array($subcommand, array('remove'), STRICT_COMPARISON)) {
                    $this->config['baseline_id'] = array_shift($args);
                } elseif (in_array($subcommand, array('save'), STRICT_COMPARISON)) {
                    $this->config['baseline_set'] = array_shift($args);
                }
            }
        } else {
            $this->config['command']       = 'unknown';
            $this->config['command_value'] = $command ?? '<no-command>';
        }

        if (!empty($args)) {
            $c = count($args);
            if (isset($this->config['verbose'])) {
                display( 'Found ' . $c . ' argument' . ($c > 1 ? 's' : '') . ' that ' . ($c > 1 ? 'are' : 'is') . " not understood.\n\n\"" . implode('", "', $args) . "\"\n\nIgnoring " . ($c > 1 ? 'them all' : 'it' . ".\n"));
            }
        }

        // Special case for onepage command. It will only work on 'onepage' project
        if ($this->config['command'] == 'onepage') {

            $this->config['project']   = 'onepage';
            $this->config['ruleset']   = 'OneFile';

            $this->config['format']    = array('OnepageJson');
            $this->config['file']      = str_replace('/code/', '/reports/', substr($this->config['filename'], 0, -4));
            $this->config['quiet']     = true;
            $this->config['norefresh'] = true;
        }

        return 'commandline';
    }
}

?>