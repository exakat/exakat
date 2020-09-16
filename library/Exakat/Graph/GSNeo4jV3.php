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

namespace Exakat\Graph;

use Exakat\Graph\Helpers\GraphResults;
use Exakat\Exceptions\GremlinException;
use Exakat\Exceptions\UnknownGremlinVersion;
use Brightzone\GremlinDriver\Connection;

class GSNeo4jV3 extends Graph {
    const CHECKED     = true;
    const UNCHECKED   = false;
    const UNAVAILABLE = 2;

    private $status     = self::UNCHECKED;

    private $db         = null;

    private $gremlinVersion = '3.4';

    public function getInfo(): array {
        $stats = array();

        if (empty($this->config->gsneo4jv3_folder)) {
            $stats['configured'] = 'No gsneo4jv3_folder configured in config/exakat.ini.';
        } elseif (!file_exists($this->config->gsneo4jv3_folder)) {
            $stats['installed'] = 'No (folder : ' . $this->config->gsneo4jv3_folder . ')';
        } elseif (!file_exists($this->config->gsneo4jv3_folder . '/ext/neo4j-gremlin/')) {
            $stats['installed'] = 'Partially (missing neo4j folder : ' . $this->config->gsneo4jv3_folder . ')';
        } else {
            $stats['installed'] = "Yes (folder : {$this->config->gsneo4jv3_folder})";
            $stats['host'] = $this->config->gsneo4jv3_host;
            $stats['port'] = $this->config->gsneo4jv3_port;

            $plugins = glob("{$this->config->gsneo4jv3_folder}/ext/neo4j-gremlin/plugin/*.jar");
            if (count($plugins) !== 72) {
                $stats['grapes failed'] = 'Partially installed neo4j plugin. Please, check installation docs, and "grab" again : some of the files are missing for neo4j.';
            }

            $gremlinJar = glob("{$this->config->gsneo4jv3_folder}/lib/gremlin-core-*.jar");
            $gremlinVersion = basename(array_pop($gremlinJar));
            //gremlin-core-3.2.5.jar
            $gremlinVersion = substr($gremlinVersion, 13, -4);
            $stats['gremlin version'] = $gremlinVersion;

            $neo4jJar = glob("{$this->config->gsneo4jv3_folder}/ext/neo4j-gremlin/lib/neo4j-*.jar");
            $neo4jJar = array_filter($neo4jJar, function ($x) { return preg_match('#/neo4j-\d\.\d\.\d\.jar#', $x); });
            $neo4jVersion = basename(array_pop($neo4jJar));

            //neo4j-2.3.3.jar
            $neo4jVersion = substr($neo4jVersion, 6, -4);
            $stats['neo4j version'] = $neo4jVersion;

            if (file_exists("{$this->config->gsneo4jv3_folder}/db/gsneo4jv3.pid")) {
                $stats['running'] = 'Yes (PID : ' . trim(file_get_contents("{$this->config->gsneo4jv3_folder}/db/gsneo4jv3.pid")) . ')';
            }
        }

        return $stats;
    }

    public function init(): void {
        if (!file_exists("{$this->config->gsneo4jv3_folder}/lib/")) {
            // No local production, just skip init.
            $this->status = self::UNAVAILABLE;
            return;
        }

        $gremlinJar = glob("{$this->config->gsneo4jv3_folder}/lib/gremlin-core-*.jar");
        $gremlinVersion = basename(array_pop($gremlinJar));
        // 3.4 only
        $this->gremlinVersion = substr($gremlinVersion, 13, -6);
        if(!in_array($this->gremlinVersion, array('3.4'), STRICT_COMPARISON)) {
            throw new UnknownGremlinVersion($this->gremlinVersion);
        }

        $this->db = new Connection(array( 'host'     => $this->config->gsneo4jv3_host,
                                          'port'     => $this->config->gsneo4jv3_port,
                                          'graph'    => 'graph',
                                          'emptySet' => true,
                                   ) );
                                           $this->db = new Connection(array( 'host'     => $this->config->gsneo4jv3_host,
                                          'port'     => $this->config->gsneo4jv3_port,
                                          'graph'    => 'graph',
                                          'emptySet' => true,
                                   ) );

        $this->db->message->registerSerializer('\Exakat\Graph\Helpers\GraphsonV3', true);
        $this->status = self::UNCHECKED;
    }

    private function checkConfiguration(): void {
        ini_set('default_socket_timeout', '1600');
        $this->db->open();
    }

    public function query(string $query, array $params = array(),array $load = array()): GraphResults {
        if ($this->status === self::UNAVAILABLE) {
            return new GraphResults();
        }

        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        $params['#jsr223.groovy.engine.keep.globals'] = 'phantom';
        foreach ($params as $name => $value) {
            $this->db->message->bindValue($name, $value);
        }

        $result = $this->db->send($query);


        return new GraphResults($result);
    }

    public function queryOne(string $query, array $params = array(),array $load = array()): GraphResults {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        return $this->query($query, $params, $load);
    }

    public function checkConnection(): bool {
        $res = @stream_socket_client("tcp://{$this->config->gsneo4jv3_host}:{$this->config->gsneo4jv3_port}",
                                     $errno,
                                     $errorMessage,
                                     1,
                                     STREAM_CLIENT_CONNECT
                                     );

        return is_resource($res);
    }

    public function serverInfo(): array {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        $res = $this->query('Gremlin.version();');

        return $res->toArray();
    }

    public function clean(): void {
        $this->stop();
        $this->start();
    }

    public function start(): void {
        if (!file_exists("{$this->config->gsneo4jv3_folder}/conf")) {
            throw new GremlinException('No graphdb found.');
        }

        if (!file_exists("{$this->config->gsneo4jv3_folder}/conf/gsneo4jv3.{$this->gremlinVersion}.yaml")) {
            copy( "{$this->config->dir_root}/server/gsneo4jv3/gsneo4jv3.{$this->gremlinVersion}.yaml",
                  "{$this->config->gsneo4jv3_folder}/conf/gsneo4jv3.{$this->gremlinVersion}.yaml");
            copy( "{$this->config->dir_root}/server/gsneo4jv3/exakat.properties",
                  "{$this->config->gsneo4jv3_folder}/conf/exakat.properties");
        }

        if (in_array($this->gremlinVersion, array('3.4'))) {
            display("start gremlin server {$this->gremlinVersion}.x");
            putenv("GREMLIN_YAML=conf/gsneo4jv3.{$this->gremlinVersion}.yaml");
            putenv('PID_DIR=db');
            exec("GREMLIN_YAML=conf/gsneo4jv3.{$this->gremlinVersion}.yaml; PID_DIR=db; cd {$this->config->gsneo4jv3_folder}; rm -rf db/neo4j; /bin/bash ./bin/gremlin-server.sh start > gremlin.log 2>&1 &");
        }
        display('started gremlin server');
        $this->init();
        sleep(2);

        $b = microtime(true);
        $round = -1;
        $pid = false;
        do {
            $connexion = $this->checkConnection();
            if (!$connexion) {
                ++$round;
                usleep(100000 * $round);
            }
        } while ( !$connexion && $round < 20);
        $e = microtime(true);

        display("Restarted in $round rounds\n");

        if (file_exists("{$this->config->gsneo4jv3_folder}/db/gremlin.pid")) {
            $pid = trim(file_get_contents("{$this->config->gsneo4jv3_folder}/db/gremlin.pid"));
        } elseif ( file_exists("{$this->config->gsneo4jv3_folder}/db/gsneo4jv3.pid")) {
            $pid = trim(file_get_contents("{$this->config->gsneo4jv3_folder}/db/gsneo4jv3.pid"));
        } else {
            $pid = false;
        }

        $ms = number_format(($e - $b) * 1000, 2);
        $pid = $pid === false ? 'Not yet' : $pid;
        display("started [$pid] in $ms ms");
    }

    public function stop(): void {
        if (file_exists("{$this->config->gsneo4jv3_folder}/db/gremlin.pid")) {
            display('stop gremlin server 3.4.x');
            putenv('GREMLIN_YAML=conf/gsneo4jv3.3.4.yaml');
            putenv('PID_DIR=db');
            shell_exec("GREMLIN_YAML=conf/gsneo4jv3.3.4.yaml; PID_DIR=db; cd {$this->config->gsneo4jv3_folder}; ./bin/gremlin-server.sh stop; rm -rf db/gremlin.pid");
        }
    }

    public function fixId($id) {
        return $id - 1;
    }
}

?>
