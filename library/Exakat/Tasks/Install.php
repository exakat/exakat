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

namespace Exakat\Tasks;

class Install extends Tasks {
    const CONCURENCE = self::NONE;

    const TINKERGRAPH_VERSION = '3.4.8';

    public function run(): void {
        $error = array();

        $res = shell_exec('java -version 2>&1');
        if (strpos($res, 'java version') === false) {
            $error = 'Please install Java 1.8';
        } else {
            print "Java 1.8 : OK\n";
        }

        $res = shell_exec('zip -help 2>&1');
        if (strpos($res, 'Zip 3.0') === false) {
            $error[] = 'Please install Zip 3.0 or more recent';
        } else {
            print "Zip 3.0 : OK\n";
        }

        if (!empty($error)) {
            $error[] = 'Fix the above ' . count($error) . " and try again\n";
            print implode(PHP_EOL, $error) . PHP_EOL;
            die();
        }

        if (file_exists($this->config->projects_root . '/tinkergraph') && is_dir($this->config->projects_root . '/tinkergraph')) {
            print "Tinkergraph is already installed. Omitting\n";
        } else {
            $tinkerpop = file_get_contents('https://www.exakat.io/versions/apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . '-bin.zip');

            if (hash('sha256', $tinkerpop) !== substr(file_get_contents('https://www.exakat.io/versions/apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . '-bin.zip.sha256') ?? '', 0, 64)) {
                die('SHA256 checksum doesn\'t match the downloaded version of tinkerpop. Aborting install' . PHP_EOL);
            } else {
                print "Gremlin server checksum OK\n";
            }
            file_put_contents($this->config->projects_root . '/apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . '-bin.zip', $tinkerpop);

            // Install tinkergraph
            shell_exec('cd ' . $this->config->projects_root . '; unzip apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . '-bin.zip; mv apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . ' tinkergraph; rm -rf apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . '-bin.zip');
            print "Tinkergraph installed\n";
        }

        if (file_exists($this->config->projects_root . '/tinkergraph/ext/neo4j-gremlin')) {
            print "Neo4j for gremlin is already installed. Omitting\n";
        } else {
            // Install neo4j
            shell_exec('cd ' . $this->config->projects_root . '/tinkergraph; ./bin/gremlin-server.sh install org.apache.tinkerpop neo4j-gremlin ' . self::TINKERGRAPH_VERSION);
            print "Neo4j for Tinkergraph installed\n";
        }

        shell_exec(PHP_BINARY . ' ' . $this->config->executable . ' doctor');

        $ini = file_get_contents($this->config->projects_root . '/config/exakat.ini');
        if (preg_match('/graphdb\s*=\s*\'nogremlin\';/', $ini)) {
            // check for nogremlin configuration
            if (file_exists($this->config->projects_root . '/tinkergraph/ext/neo4j-gremlin')) {
                $ini = preg_replace('/graphdb\s*=\s*\'nogremlin\';/', 'graphdb\s*=\s*\'gsneo4jv3\';', $ini);
                $ini = str_replace(';gsneo4jv3_', 'gsneo4jv3_', $ini);

                file_put_contents($this->config->projects_root . '/config/exakat.ini', $ini);
            }
        } else {
            $ini = preg_replace('/graphdb\s*=\s*\'nogremlin\';/', 'graphdb\s*=\s*\'tinkergraphv3\';', $ini);
            $ini = str_replace(';tinkergraphv3_', 'tinkergraphv3_', $ini);

            file_put_contents($this->config->projects_root . '/config/exakat.ini', $ini);
        }

        print shell_exec(PHP_BINARY . ' ' . $this->config->executable . ' doctor');
    }
}

?>