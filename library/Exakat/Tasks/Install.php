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

    const TINKERGRAPH_VERSION = '3.4.6';

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
            $error[] = 'Please install Zip 3.0';
        } else {
            print "Zip 3.0 : OK\n";
        }

        if (!empty($error)) {
            $errors[] = 'Fix the above ' . count($error) . " and try again\n";
            print implode(PHP_EOL, $error) . PHP_EOL;
            die();
        }

        if (file_exists('./tinkergraph') && is_dir('./tinkergraph')) {
            print "Tinkergraph is already installed. Omitting\n";
        } else {
            $tinkerpop = file_get_contents('https://www.exakat.io/versions/apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . '-bin.zip');
            file_put_contents('./apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . '-bin.zip', $tinkerpop);

            // Install tinkergraph
            shell_exec('unzip apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . '-bin.zip; mv apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . ' tinkergraph; rm -rf apache-tinkerpop-gremlin-server-' . self::TINKERGRAPH_VERSION . '-bin.zip');
            print "Tinkergraph installed\n";
        }

        if (file_exists('./tinkergraph/ext/neo4j-gremlin')) {
            print "Neo4j for gremlin is already installed. Omitting\n";
        } else {
            // Install neo4j
            shell_exec('cd tinkergraph; ./bin/gremlin-server.sh install org.apache.tinkerpop neo4j-gremlin ' . self::TINKERGRAPH_VERSION);
            print "Neo4j for Tinkergraph installed\n";
        }

        print shell_exec('php exakat.phar doctor');
    }
}

?>