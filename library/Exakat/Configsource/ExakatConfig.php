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

use Exakat\Phpexec;

class ExakatConfig extends Config {
    private $projects_root = '';

    private $gremlins = array( 'neo4j'       => 'Gremlin3',
                               'tinkergraph' => 'Tinkergraph',
                               'gsneo4j'     => 'GSNeo4j',
                               'janusgraph'  => 'Janusgraph',
                               'januscaes'   => 'JanusCaES',
                               'nogremlin'   => 'NoGremlin',
                               'tcsv'        => 'Tcsv',
                               );

    private $loaders = array( 'neo4j'       => 'Neo4jImport', // Could be Neo4jImport, CypherG3
                              'tinkergraph' => 'Tinkergraph',
                              'gsneo4j'     => 'SplitGraphson',
                              'janusgraph'  => 'Janusgraph',
                              'januscaes'   => 'JanusCaES',
                              'nogremlin'   => 'NoLoader',
                              'tcsv'        => 'Tcsv',
                              );

    public function __construct($projects_root) {
        $this->projects_root = $projects_root;
    }

    public function loadConfig($args) {
        $inis = array();
        
        // Default values
        $inis[] = array('graphdb'            => 'gsneo4j',
                        'gremlin'            => $this->gremlins['gsneo4j'],
                        'loader'             => $this->loaders['gsneo4j'],
                        'other_php_versions' => array(),
                       );

        $configFiles = array( $this->projects_root.'/config/exakat.ini',
                             '/etc/exakat/exakat.ini',
                             '/etc/exakat.ini',
                              
                             );

        // Parse every available init file, and stop at the first we find
        $ini = null;
        foreach($configFiles as $id => $configFile) {
            if (file_exists($configFile)) {
                $inis = parse_ini_file($configFile);
                $optionFiles = $configFile;
            } 
        }

        if ( $inis === null) {
            return self::NOT_LOADED;
        }
        
        $this->config = $inis;

        // Validation
        if (!in_array($this->config['graphdb'], array_keys($this->gremlins)) ) {
            $this->config['graphdb'] = 'gsneo4j';
        }

        $graphdb = $this->config['graphdb'];
        foreach($this->gremlins as $gdb => $foo) {
            if (isset($this->config[$gdb.'_folder'])) {
                if ($this->config[$gdb.'_folder'][0] !== '/') {
                    $this->config[$gdb.'_folder'] = $this->projects_root.'/'.$this->config[$gdb.'_folder'];
                }
                $this->config[$gdb.'_folder'] = realpath($this->config[$gdb.'_folder']);
            }
        }

        // Update values with actual loaders and gremlin
        $this->config['gremlin'] = $this->gremlins[$this->config['graphdb']];
        $this->config['loader']  = $this->loaders[$this->config['graphdb']];

        foreach(self::PHP_VERSIONS as $version) {
            if (empty($this->config['php'.$version])) {
                continue;
            }
            $php = new Phpexec($version[0].'.'.$version[1], $this->config['php'.$version]);
            if ($php->isValid()) {
                $this->config['other_php_versions'][] = $version;
            }
        }

        return 'config/exakat.ini';
    }
}

?>