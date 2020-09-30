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

use Exakat\Phpexec;
use Exakat\Project;
use Exakat\Config as MainConfig;
use Exakat\Exceptions\NoPhpBinary;

class ExakatConfig extends Config {
    private $projects_root = '';

    private $gremlins = array( 'tinkergraph'   => 'Tinkergraph',
                               'tinkergraphv3' => 'TinkergraphV3',
                               'gsneo4j'       => 'GSNeo4j',
                               'gsneo4jv3'     => 'GSNeo4jV3',
                               'janusgraph'    => 'Janusgraph',
                               'nogremlin'     => 'NoGremlin',
                               'orientdb'      => 'Orientdb',
                               'bitsy'         => 'Bitsy',
                               );

    private $loaders = array( 'tinkergraph'   => 'SplitGraphson',
                              'tinkergraphv3' => 'SplitGraphson',
                              'gsneo4j'       => 'SplitGraphson',
                              'gsneo4jv3'     => 'SplitGraphson',
                              'janusgraph'    => 'SplitGraphson',
                              'orientdb'      => 'SplitGraphson',
                              'bitsy'         => 'SplitGraphson',
                              'nogremlin'     => 'None',
                              );

    public function __construct(string $projects_root) {
        $this->projects_root = $projects_root;
    }

    public function loadConfig(Project $project) : ?string {
        // Default values
        $inis =  array(
                    array('graphdb'            => 'gsneo4jv3',
                          'gremlin'            => $this->gremlins['gsneo4jv3'],
                          'loader'             => $this->loaders['gsneo4jv3'],
                          'other_php_versions' => array(),
                          'transit_key'        => '',
                       )
                    );

        $configFiles = array("{$this->projects_root}/config/exakat.ini",
                             '/etc/exakat/exakat.ini',
                             '/etc/exakat.ini',
                             '', // This is the canary : when all fail, this will be used and returned
                             );

        // Attempt each init path, and stop at the first file we find
        $ini = null;
        foreach($configFiles as $configFile) {
            if (file_exists($configFile)) {
                // overwrite existing with the new, keep the default values
                $ini = @parse_ini_file($configFile);
                if (is_array($ini)) {
                    $inis = $ini + $inis[0];
                    break 1;
                } else {
                    $error = error_get_last();
                    print "Invalid config file '$configFile' : $error[message]Ignoring '$configFile'\n\n";
                }
            }
        }

        // Aliasing project_themes into rulesets
        if (isset($inis['project_themes'])) {
            print "Rename project_themes in project_rulesets, in your config/exakat.ini file\n";

            if (empty($inis['project_rulesets'])) {
                $inis['project_rulesets'] = $inis['project_themes'];
            }
        }
        $this->config = $inis;

        // Validation
        if (!isset($this->config['graphdb']) ||
            !in_array($this->config['graphdb'], array_keys($this->gremlins)) ) {
            display('Warning : No such graph as ' . ($this->config['graphdb'] ?? '') . " : using 'nogremlin', without graphdb.\n");
            $this->config['graphdb'] = 'nogremlin';
        }

        foreach(array_keys($this->gremlins) as $gdb) {
            $folder = "{$gdb}_folder";
            if (isset($this->config[$folder])) {
                if ($this->config[$folder][0] !== '/') {
                    $this->config[$folder] = "{$this->projects_root}/{$this->config[$folder]}";
                }
                $this->config[$folder] = realpath($this->config[$folder]);
            }
        }

        // Update values with actual loaders and gremlin
        $this->config['gremlin'] = $this->gremlins[$this->config['graphdb']];
        $this->config['loader']  = $this->loaders[$this->config['graphdb']];

        if (isset($this->config['concurencyCheck'])) {
            $this->config['concurencyCheck'] = (int) $this->config['concurencyCheck'];
            if ($this->config['concurencyCheck'] < 1024) {
                $this->config['concurencyCheck'] = 7610;
            } elseif ($this->config['concurencyCheck'] > 49150) {
                $this->config['concurencyCheck'] = 7610;
            }
        }

        foreach(MainConfig::PHP_VERSIONS as $version) {
            if (empty($this->config["php$version"])) {
                continue;
            }
            try {
                $php = new Phpexec("$version[0].$version[1]", $this->config["php$version"]);
            } catch (NoPhpBinary $e) {
                continue;
            }

            if ($php->isValid()) {
                $this->config['other_php_versions'][] = $version;
            }
        }

        // Calculate the stubs recursivement if it is a folder
        // all path are absolute, may be placed anywhere
        if (!isset($this->config['stubs'])) {
            $this->config['stubs'] = array();
        } else {
            $stubs = array(array());
            $this->config['stubs'] = makeArray($this->config['stubs']);
            foreach($this->config['stubs'] as $stub) {
                $d = getcwd();
                $path = realpath($stub);

                if ($path === false) {
                    continue;
                }

                if (!file_exists($path)) {
                    $stubs[$stub] = array();

                    continue;
                }

                if (is_file($path)) {
                    $stubs[$stub] = array($stub);

                    continue;
                }

                if (is_dir($path)) {
                    chdir($path);
                    $allFiles = rglob('.');
                    $allFiles = array_map(function ($path) use ($stub) { return $stub . ltrim($path, '.'); }, $allFiles);
                    chdir($d);

                    $stubs[$stub] = $allFiles;
                }
            }
            $this->config['stubs'] = array_unique(array_merge(...array_values($stubs)));
        }

        return str_replace(getcwd(), '.', $configFile);
    }
}

?>