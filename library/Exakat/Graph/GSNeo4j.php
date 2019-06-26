<?php
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

use Exakat\Graph\Graph;
use Exakat\Graph\Helpers\GraphResults;
use Exakat\Exceptions\GremlinException;
use Exakat\Tasks\Tasks;
use Brightzone\GremlinDriver\Connection;
use stdClass;

class GSNeo4j extends Graph {
    const CHECKED     = true;
    const UNCHECKED   = false;
    const UNAVAILABLE = 2;
    
    private $status     = self::UNCHECKED;
    
    private $db         = null;

    private $gremlinVersion = '3.3';
    
    public function __construct($config) {
        parent::__construct($config);

        if (!file_exists("{$this->config->gsneo4j_folder}/lib/")) {
            // No local production, just skip init.
            $this->status = self::UNAVAILABLE;
            return;
        }
        
        $gremlinJar = preg_grep('/gremlin-core-([0-9\.]+)\\.jar/', scandir("{$this->config->gsneo4j_folder}/lib/"));
        $gremlinVersion = basename(array_pop($gremlinJar));
        // 3.4 or 3.3 or 3.2
        $this->gremlinVersion = substr($gremlinVersion, 13, -6);
        assert(in_array($this->gremlinVersion, array('3.2', '3.3', '3.4')), "Unknown Gremlin version : $this->gremlinVersion\n");

        $this->db = new Connection(array( 'host'     => $this->config->gsneo4j_host,
                                          'port'     => $this->config->gsneo4j_port,
                                          'graph'    => 'graph',
                                          'emptySet' => true,
                                   ) );
    }
    
    public function resetConnection() {
        unset($this->db);
        $this->db = new Connection(array( 'host'     => $this->config->gsneo4j_host,
                                          'port'     => $this->config->gsneo4j_port,
                                          'graph'    => 'graph',
                                          'emptySet' => true,
                                   ) );
        $this->status = self::UNCHECKED;
    }
    
    private function checkConfiguration() {
        ini_set('default_socket_timeout', 1600);
        $this->db->open();
    }

    public function query($query, $params = array(), $load = array()) {
        if ($this->status === self::UNAVAILABLE) {
            return new GraphResults();
        } elseif ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        $params['#jsr223.groovy.engine.keep.globals'] = 'phantom';
        foreach($params as $name => $value) {
            $this->db->message->bindValue($name, $value);
        }
        
        static $query_count = 0;
        ++$query_count;
        $b = hrtime(true);
        $result = $this->db->send($query);
        $e = hrtime(true);
        $d = ( ($e - $b) / 1000000 );
        file_put_contents('./gremlin.query.log', "$query_count\t$d\t$query\n", \FILE_APPEND);

        if (empty($result)) {
            return new GraphResults();
        } elseif($result[0] === null) {
            return new GraphResults();
        } elseif(is_array($result[0])) {
            if (isset($result[0]['processed'])) {
                $result = array('processed' => empty($result[0]['processed']) ? 0 : array_shift($result[0]['processed']),
                                'total'     => empty($result[0]['total'])     ? 0 : array_shift($result[0]['total']));
            }

            if (isset($result[0]['type'])) {
                $result = $this->simplifyArray($result);
            }

            return new GraphResults($result);
        } elseif (is_array($result)) {
            return new GraphResults($result);
        } elseif ($result instanceof stdClass) {
            return new GraphResults($result);
        } else {
            print 'Processing unknown type ' . gettype($result) . PHP_EOL;
            die(__METHOD__);
        }
    }
    
    public function queryOne($query, $params = array(), $load = array()) {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        $res = $this->query($query, $params, $load);
        if (!($res instanceof stdClass) || !isset($res->results)) {
            throw new GremlinException('Server is not responding');
        }
        
        if (is_array($res->results)) {
            return $res->results[0];
        } else {
            return $res->results;
        }
    }

    public function checkConnection() {
        $res = @stream_socket_client("tcp://{$this->config->gsneo4j_host}:{$this->config->gsneo4j_port}",
                                     $errno,
                                     $errorMessage,
                                     1,
                                     STREAM_CLIENT_CONNECT
                                     );

        return is_resource($res);
    }

    public function serverInfo() {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }
        
        $res = $this->query('Gremlin.version();');

        return $res;
    }

    public function clean() {
        $this->stop();
        $this->start();
    }
    
    public function start() {
        if (!file_exists("{$this->config->gsneo4j_folder}/conf")) {
            throw new GremlinException('No graphdb found.');
        }
        
        if (!file_exists("{$this->config->gsneo4j_folder}/conf/gsneo4j.{$this->gremlinVersion}.yaml")) {
            copy( "{$this->config->dir_root}/server/gsneo4j/gsneo4j.{$this->gremlinVersion}.yaml",
                  "{$this->config->gsneo4j_folder}/conf/gsneo4j.{$this->gremlinVersion}.yaml");
            copy( "{$this->config->dir_root}/server/gsneo4j/exakat.properties",
                  "{$this->config->gsneo4j_folder}/conf/exakat.properties");
        }

        if (in_array($this->gremlinVersion, array('3.3', '3.4'))) {
            display("start gremlin server {$this->gremlinVersion}.x");
            putenv("GREMLIN_YAML=conf/gsneo4j.{$this->gremlinVersion}.yaml");
            putenv('PID_DIR=db');
            exec("GREMLIN_YAML=conf/gsneo4j.{$this->gremlinVersion}.yaml; PID_DIR=db; cd {$this->config->gsneo4j_folder}; rm -rf db/neo4j; ./bin/gremlin-server.sh start > gremlin.log 2>&1 &");
        } elseif ($this->gremlinVersion === '3.2') {
            display('start gremlin server 3.2.x');
            exec("cd {$this->config->gsneo4j_folder}; rm -rf db/neo4j; ./bin/gremlin-server.sh conf/gsneo4j.3.2.yaml  > gremlin.log 2>&1 & echo $! > db/gsneo4j.pid ");
        }
        display('started gremlin server');
        $this->resetConnection();
        sleep(2);
        
        $b = microtime(true);
        $round = -1;
        $pid = false;
        do {
            $connexion = $this->checkConnection();
            if (empty($connexion)) {
                ++$round;
                usleep(100000 * $round);
            }
        } while ( empty($connexion) && $round < 20);
        $e = microtime(true);
        
        display("Restarted in $round rounds\n");

        if (file_exists("{$this->config->gsneo4j_folder}/db/gremlin.pid")) {
            $pid = trim(file_get_contents("{$this->config->gsneo4j_folder}/db/gremlin.pid"));
        } elseif ( file_exists("{$this->config->gsneo4j_folder}/db/gsneo4j.pid")) {
            $pid = trim(file_get_contents("{$this->config->gsneo4j_folder}/db/gsneo4j.pid"));
        } else {
            $pid = false;
        }

        $ms = number_format(($e - $b) * 1000, 2);
        $pid = $pid === false ? 'Not yet' : $pid;
        display("started [$pid] in $ms ms");
    }

    public function stop() {
        if (file_exists("{$this->config->gsneo4j_folder}/db/gremlin.pid")) {
            display('stop gremlin server 3.3.x');
            putenv('GREMLIN_YAML=conf/gsneo4j.3.3.yaml');
            putenv('PID_DIR=db');
            shell_exec("GREMLIN_YAML=conf/gsneo4j.3.3.yaml; PID_DIR=db; cd {$this->config->gsneo4j_folder}; ./bin/gremlin-server.sh stop; rm -rf db/gremlin.pid");
        }
        
        if (file_exists("{$this->config->gsneo4j_folder}/db/gsneo4j.pid")) {
            display('stop gremlin server 3.2.x');
            shell_exec("kill -9 \$(cat {$this->config->gsneo4j_folder}/db/gsneo4j.pid) 2>> gremlin.log; rm -f {$this->config->gsneo4j_folder}/db/gsneo4j.pid");
        }
    }

    private function simplifyArray($result) {
        $return = array();
        
        if (!isset($result[0]['properties'])) {
            return $result;
        }

        foreach($result as $r) {
            $row = array('id'    => $r['id'],
                         'label' => $r['label']);
            foreach($r['properties'] as $property => $value) {
                $row[$property] = $value[0]['value'];
            }
            
            $return[] = $row;
        }
        
        return $return;
    }
    
    public function getDefinitionSQL() {
        // Optimize loading by sorting the results
        return <<<'SQL'
SELECT DISTINCT CASE WHEN definitions.id IS NULL THEN definitions2.id - 1 ELSE definitions.id - 1 END AS definition, calls.id - 1 AS call
FROM calls
LEFT JOIN definitions 
    ON definitions.type       = calls.type       AND
       definitions.fullnspath = calls.fullnspath
LEFT JOIN definitions definitions2
    ON definitions2.type       = calls.type       AND
       definitions2.fullnspath = calls.globalpath 
WHERE (definitions.id IS NOT NULL OR definitions2.id IS NOT NULL)
ORDER BY definition, call
SQL;
    }
}

?>
