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
 
                               'git'            => true,
                               'svn'            => false,
                               'bzr'            => false,
                               'hg'             => false,
                               'composer'       => false,
                               'tgz'            => false,
                               'tbz'            => false,
                               'zip'            => false,
                               'rar'            => false,
 
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
                               'file'           =>  null,
                               'style'          => 'ALL',
 
                               'gsneo4j_host'       => '127.0.0.1',
                               'gsneo4j_port'       => '7474',
                               'gsneo4j_folder'     => 'tinkergraph',
                               
                               'tinkergraph_host'   => '127.0.0.1',
                               'tinkergraph_port'   => '7474',
                               'tinkergraph_folder' => 'tinkergraph',

                               'bitsy_host'         => '127.0.0.1',
                               'bitsy_port'         => '8182',
                               'bitsy_folder'       => 'tinkergraph',
 
                               'branch'         => 'master',
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
 
                               'phpversion'    => '7.2',
                               'token_limit'   => '1000000',
                               
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
                               
                               'remote'              => 'none',
 
                               'project_reports'     => array('Ambassador',
                                                             ),
                               'project_themes'      => array('CompatibilityPHP53', 
                                                              'CompatibilityPHP54', 
                                                              'CompatibilityPHP55', 
                                                              'CompatibilityPHP56', 
                                                              'CompatibilityPHP70', 
                                                              'CompatibilityPHP71', 
                                                              'CompatibilityPHP72', 
                                                              'CompatibilityPHP73',
                                                              'Dead code', 
                                                              'Security', 
                                                              'Analyze', 
                                                              'Preferences',
                                                              'Appinfo', 
                                                              'Appcontent',
                                                              ),
                               
                              );

    public function loadConfig($args) {
    }
}

?>