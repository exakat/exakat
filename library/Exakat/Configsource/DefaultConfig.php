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

use Exakat\Config as Configuration;
use Exakat\Project;

class DefaultConfig extends Config {
    protected $config  = array( // directives with boolean value
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
                               'debug'          => false,

                               'collect'        => false,

                               'git'            => true,
                               'svn'            => false,
                               'bzr'            => false,
                               'hg'             => false,
                               'composer'       => false,
                               'tgz'            => false,
                               'tbz'            => false,
                               'zip'            => false,
                               'rar'            => false,
                               'seven7'         => false,

                                // directives with literal value
                               'filename'           => '',
                               'dirname'            => '',
                               'program'            => '',
                               'repository'         => false,
                               'analyzers'          => array(),
                               'report'             => 'Diplomat',
                               'file'               =>  '',
                               'style'              => 'ALL',

                               'gsneo4j_host'       => '127.0.0.1',
                               'gsneo4j_port'       => '7474',
                               'gsneo4j_folder'     => 'tinkergraph',

                               'tinkergraph_host'   => '127.0.0.1',
                               'tinkergraph_port'   => '7474',
                               'tinkergraph_folder' => 'tinkergraph',

                               'branch'         => '',
                               'tag'            => '',

                               'php'           => PHP_BINARY,
                               'php52'         => '',
                               'php53'         => '',
                               'php54'         => '',
                               'php55'         => '',
                               'php56'         => '',
                               'php70'         => '',
                               'php71'         => '',
                               'php72'         => '',
                               'php73'         => '',
                               'php74'         => '',
                               'php80'         => '',

                               'phpversion'    => '7.4',
                               'token_limit'   => '1000000',

                               'baseline_use'  => 'last',    // none, last, name, number
                               'baseline_set'  => 'one',   // none, one, always

                               'concurencyCheck' => 7610,

                               'command'       => 'version',

                               'include_dirs'        => array('',
                                                             ),
                               'ignore_dirs'         => array('/assets',
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
                               'file_extensions'     => array('php', 'php3', 'inc', 'tpl', 'phtml', 'tmpl', 'phps', 'ctp', 'module'),
                               'project_name'        => '',
                               'project_url'         => '',
                               'project_vcs'         => 'git',
                               'project_description' => '',
                               'project_packagist'   => '',
                               'other_php_versions'  => array(),

                               'ignore_rules'        => array(),

                               'remote'              => 'none',

                               'project_reports'     => array('Diplomat',
                                                             ),
                               'project_rulesets'    => array('CompatibilityPHP70',
                                                              'CompatibilityPHP71',
                                                              'CompatibilityPHP72',
                                                              'CompatibilityPHP73',
                                                              'CompatibilityPHP74',
                                                              'CompatibilityPHP80',
                                                              'Suggestions',
                                                              'Dead code',
                                                              'Security',
                                                              'Analyze',
                                                              'Top10',
                                                              'Preferences',
                                                              'Appinfo',
                                                              'Appcontent',
                                                              'Suggestions',
                                                              ),

                                'inside_code'          => Configuration::WITH_PROJECTS,

                                'php_extensions'       => array('all'),
                              );

    public function __construct() {
        $this->config['project'] = new Project();
    }

    public function loadConfig(Project $project) : ?string {
        return 'default';
    }
}

?>