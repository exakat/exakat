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

class Tinkergraph extends Graph {
    const CHECKED = true;
    const UNCHECKED = false;
    const UNAVAILABLE = 1;

    private $status     = self::UNCHECKED;
    private $db         = null;

    private $gremlinVersion = '3.4';

    public function init(): void {
        if (!file_exists("{$this->config->tinkergraph_folder}/lib/")) {
            // No local production, just skip init.
            $this->status = self::UNAVAILABLE;
            return;
        }

        $gremlinJar = glob("{$this->config->tinkergraph_folder}/lib/gremlin-core-*.jar");
        $gremlinVersion = basename(array_pop($gremlinJar));

        $this->gremlinVersion = substr($gremlinVersion, 13, -6);
        if (in_array($this->gremlinVersion, array('3.4'), STRICT_COMPARISON)) {
            throw new UnknownGremlinVersion($this->gremlinVersion);
        }

        $this->db = new Connection(array( 'host'  => $this->config->tinkergraph_host,
                                          'port'  => $this->config->tinkergraph_port,
                                          'graph' => 'graph',
                                          'emptySet' => true,
                                   ) );
        $this->status = self::UNCHECKED;
    }

    private function checkConfiguration(): void {
        ini_set('default_socket_timeout', '1600');
        $this->db->open();
    }

    public function getInfo(): array {
        $stats = array();

        if (empty($this->config->tinkergraphv3_folder)) {
            $stats['configured'] = 'No tinkergraph configured in config/exakat.ini.';
        } elseif (!file_exists($this->config->tinkergraphv3_folder)) {
            $stats['installed'] = 'No (folder : ' . $this->config->tinkergraphv3_folder . ')';
        } else {
            $stats['installed'] = 'Yes (folder : ' . $this->config->tinkergraphv3_folder . ')';
            $stats['host'] = $this->config->tinkergraph_host;
            $stats['port'] = $this->config->tinkergraph_port;

            $gremlinJar = glob("{$this->config->tinkergraphv3_folder}/lib/gremlin-core-*.jar");
            $gremlinVersion = basename(array_pop($gremlinJar));
            //example : gremlin-core-3.2.5.jar
            $gremlinVersion = substr($gremlinVersion, 13, -4);

            $stats['gremlin version'] = $gremlinVersion;

            if (file_exists("{$this->config->tinkergraph_port}/db/tinkergraph.pid")) {
                $stats['running'] = 'Yes (PID : ' . trim(file_get_contents("{$this->config->tinkergraph_port}/db/tinkergraph.pid")) . ')';
            }
        }

        return $stats;
    }

    public function query(string $query, array $params = array(),array $load = array()): GraphResults {
        if ($this->status === self::UNAVAILABLE) {
            return new GraphResults();
        } elseif ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        $b = microtime(true);
        $params['#jsr223.groovy.engine.keep.globals'] = 'phantom';
        foreach ($params as $name => $value) {
            $this->db->message->bindValue($name, $value);
        }

        $result = $this->db->send($query);

        if (empty($result)) {
            return new GraphResults();
        } elseif ($result[0] === null) {
            return new GraphResults();
        } elseif (is_array($result[0])) {
            if (isset($result[0]['type'])) {
                $result = $this->simplifyArray($result);
            }

            return new GraphResults($result);
        } elseif (is_array($result)) {
            return new GraphResults($result);
        } elseif ($result instanceof \stdClass) {
            return new GraphResults($result);
        } else {
            print 'Processing unknown type ' . gettype($result) . PHP_EOL;
            var_dump($result);
            die();
        }
    }

    public function queryOne(string $query, array $params = array(),array $load = array()): GraphResults {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        return $this->query($query, $params, $load);
    }

    public function checkConnection(): bool {
        $res = @stream_socket_client('tcp://' . $this->config->tinkergraph_host . ':' . $this->config->tinkergraph_port,
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
        // This is memory only Database
        $this->stop();
        $this->start();
    }

    public function start(): void {
        if (!file_exists("{$this->config->tinkergraph_folder}/conf")) {
            throw new GremlinException('No tinkgergraph configuration folder found.');
        }

        if (!file_exists("{$this->config->tinkergraph_folder}/conf/tinkergraph.{$this->gremlinVersion}.yaml")) {
            copy("{$this->config->dir_root}/server/tinkergraph/tinkergraph.{$this->gremlinVersion}.yaml",
                 "{$this->config->tinkergraph_folder}/conf/tinkergraph.{$this->gremlinVersion}.yaml");
        }

        if (in_array($this->gremlinVersion, array('3.3', '3.4'), STRICT_COMPARISON)) {
            putenv("GREMLIN_YAML=conf/tinkergraph.{$this->gremlinVersion}.yaml");
            putenv('PID_DIR=db');
            exec("GREMLIN_YAML=conf/tinkergraph.{$this->gremlinVersion}.yaml; PID_DIR=db; cd {$this->config->tinkergraph_folder}; rm -rf db/neo4j;/bin/bash ./bin/gremlin-server.sh start > gremlin.log 2>&1 &");
        } elseif ($this->gremlinVersion === '3.2') {
            exec("cd {$this->config->tinkergraph_folder}; rm -rf db/neo4j;/bin/bash ./bin/gremlin-server.sh conf/tinkergraph.yaml  > gremlin.log 2>&1 & echo $! > db/tinkergraph.{$this->gremlinVersion}.pid ");
        } else {
            throw new GremlinException("Wrong version for tinkergraph : $this->gremlinVersion");
        }
        $this->init();
        sleep(1);

        $b = microtime(true);
        $round = -1;
        do {
            $res = $this->checkConnection();
            ++$round;
            usleep(100000 * $round);
        } while (!$res && $round < 20);
        $e = microtime(true);

        display("Restarted in $round rounds\n");

        if (file_exists("{$this->config->tinkergraph_folder}/run/gremlin.pid")) {
            $pid = trim(file_get_contents("{$this->config->tinkergraph_folder}/run/gremlin.pid"));
        } elseif (file_exists("{$this->config->tinkergraph_folder}/db/tinkergraph.pid")) {
            $pid = trim(file_get_contents("{$this->config->tinkergraph_folder}/db/tinkergraph.pid"));
        } else {
            $pid = 'Not yet';
        }

        display('started [' . $pid . '] in ' . number_format(($e - $b) * 1000, 2) . ' ms' );
    }

    public function stop(): void {
        if (file_exists("{$this->config->tinkergraph_folder}/db/gremlin.pid")) {
            display("stop gremlin server {$this->gremlinVersion}");
            putenv("GREMLIN_YAML=conf/tinkergraph.{$this->gremlinVersion}.yaml");
            putenv('PID_DIR=db');
            shell_exec("cd {$this->config->tinkergraph_folder}; ./bin/gremlin-server.sh stop");
            if (file_exists("{$this->config->tinkergraph_folder}/db/gremlin.pid")) {
                unlink("{$this->config->tinkergraph_folder}/db/gremlin.pid");
            }
        }

        if (file_exists("{$this->config->tinkergraph_folder}/db/tinkergraph.pid")) {
            display('stop gremlin server 3.2');
            shell_exec("kill -9 $(cat {$this->config->tinkergraph_folder}/db/tinkergraph.pid) 2>> gremlin.log; rm -f {$this->config->tinkergraph_folder}/db/tinkergraph.pid");
        }
    }

    private function simplifyArray(array $result): array {
        $return = array();

        if (!isset($result[0]['properties'])) {
            return $result;
        }

        foreach ($result as $r) {
            $row = array('id'    => $r['id'],
                         'label' => $r['label']);
            foreach ($r['properties'] as $property => $value) {
                $row[$property] = $value[0]['value'];
            }

            $return[] = $row;
        }

        return $return;
    }

    public function getDefinitionSQL(): string {
        // Optimize loading by sorting the results
        return <<<'SQL'
SELECT DISTINCT CASE WHEN definitions.id IS NULL THEN definitions2.id ELSE definitions.id END AS definition, GROUP_CONCAT(DISTINCT calls.id) AS call, count(calls.id) AS id
FROM calls
LEFT JOIN definitions 
    ON definitions.type       = calls.type       AND
       definitions.fullnspath = calls.fullnspath
LEFT JOIN definitions definitions2
    ON definitions2.type       = calls.type       AND
       definitions2.fullnspath = calls.globalpath 
WHERE (definitions.id IS NOT NULL OR definitions2.id IS NOT NULL)
GROUP BY definition
SQL;
    }

    public function getGlobalsSql(): string {
        return 'SELECT origin, destination FROM globals';
    }
}

?>
