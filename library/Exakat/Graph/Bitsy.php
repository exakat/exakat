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

namespace Exakat\Graph;

use Exakat\Graph\Graph;
use Exakat\Graph\Helpers\GraphResults;
use Exakat\Exceptions\UnableToReachGraphServer;
use Exakat\Exceptions\Neo4jException;
use Exakat\Exceptions\GremlinException;
use Exakat\Tasks\Tasks;
use Brightzone\GremlinDriver\Connection;
use stdClass;

class Bitsy extends Graph {
    const CHECKED = true;
    const UNCHECKED = false;
    
    private $client = null;
    
    private $scriptDir  = '';
    private $neo4j_host = '';
    private $neo4j_auth = '';
    
    private $status     = self::UNCHECKED;
    
    private $log        = null;
    private $db         = null;

    private $gremlinVersion = '';
    
    public function __construct($config) {
        parent::__construct($config);

        $gremlinJar = preg_grep('/gremlin-core-([0-9\.]+)\\.jar/', scandir("{$this->config->bitsy_folder}/lib/"));
        $gremlinVersion = basename(array_pop($gremlinJar));
        // 3.3 or 3.2
        $this->gremlinVersion = substr($gremlinVersion, 13, -6);
        assert(in_array($this->gremlinVersion, array('3.2', '3.3')), "Unknown Gremlin version : $this->gremlinVersion\n");

        $this->db = new Connection(array( 'host'     => $this->config->bitsy_host,
                                          'port'     => $this->config->bitsy_port,
                                          'graph'    => 'graph',
                                          'emptySet' => true,
                                   ) );
        $this->db->message->registerSerializer('\Brightzone\GremlinDriver\Serializers\Gson3', TRUE);
    }
    
    public function resetConnection() {
        unset($this->db);
        $this->db = new Connection(array( 'host'     => $this->config->bitsy_host,
                                          'port'     => $this->config->bitsy_port,
                                          'graph'    => 'graph',
                                          'emptySet' => true,
                                   ) );
        $this->db->message->registerSerializer('\Brightzone\GremlinDriver\Serializers\Gson3', TRUE);
        $this->status = self::UNCHECKED;
    }
    
    private function checkConfiguration() {
        ini_set('default_socket_timeout', 1600);
        $this->db->open();
    }

    public function query($query, $params = array(), $load = array()) {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        $params['#jsr223.groovy.engine.keep.globals'] = 'phantom';
        print $query."\n";
        foreach($params as $name => $value) {
            $this->db->message->bindValue($name, $value);
        }
        $result = $this->db->send($query);

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
            print "Processing unknown type ".gettype($result).PHP_EOL;
            var_dump($result);
            die();
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
        $res = @stream_socket_client("tcp://{$this->config->bitsy_host}:{$this->config->bitsy_port}",
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
        if (!file_exists("{$this->config->bitsy_folder}/conf/bitsy.yaml")) {
            copy( "{$this->config->dir_root}/server/bitsy/bitsy.yaml",
                  "{$this->config->bitsy_folder}/conf/bitsy.yaml");
            copy( "{$this->config->dir_root}/server/bitsy/bitsy.properties",
                  "{$this->config->bitsy_folder}/conf/bitsy.properties");
        }

        if ($this->gremlinVersion === '3.3') {
            display('start gremlin server 3.3.x');
            exec("cd {$this->config->bitsy_folder}; rm -rf db/bitsy; mkdir db/bitsy; ./bin/gremlin-server.sh conf/bitsy.yaml > gremlin.log 2>&1 &  echo $! > db/bitsy.pid ");
        } elseif ($version === '3.2') {
            die("Not supported");
        }
        display('started gremlin server');
        $this->resetConnection();
        sleep(1);
        
        $b = microtime(true);
        $round = -1;
        do {
            $res = $this->checkConnection();
            ++$round;
            usleep(100000 * $round);
        } while (empty($res) && $round < 20);
        $e = microtime(true);
        
        display("Restarted in $round rounds\n");

        if (file_exists("{$this->config->bitsy_folder}/run/gremlin.pid")) {
            $pid = trim(file_get_contents("{$this->config->bitsy_folder}/run/gremlin.pid"));
        } elseif ( file_exists("{$this->config->bitsy_folder}/db/bitsy.pid")) {
            $pid = trim(file_get_contents("{$this->config->bitsy_folder}/db/bitsy.pid"));
        } else {
            $pid = 'Not yet';
        }
        
        display("started [$pid] in ".number_format(($e - $b) * 1000, 2).' ms' );
    }

    public function stop() {
        if (file_exists("{$this->config->bitsy_folder}/run/gremlin.pid")) {
            display('stop gremlin server 3.3.x');
            print shell_exec("cd {$this->config->bitsy_folder}; ./bin/gremlin-server.sh stop");
        }
        
        if (file_exists($this->config->bitsy_folder.'/db/bitsy.pid')) {
            display('stop gremlin server 3.2.x');
            shell_exec("kill -9 \$(cat {$this->config->bitsy_folder}/db/bitsy.pid) 2>> gremlin.log; rm -f {$this->config->bitsy_folder}/db/bitsy.pid");
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
        return <<<SQL
SELECT DISTINCT CASE WHEN definitions.id IS NULL THEN definitions2.id - 1 ELSE definitions.id - 1 END AS definition, calls.id - 1 AS call
FROM calls
LEFT JOIN definitions 
    ON definitions.type       = calls.type       AND
       definitions.fullnspath = calls.fullnspath
LEFT JOIN definitions definitions2
    ON definitions2.type       = calls.type       AND
       definitions2.fullnspath = calls.globalpath 
WHERE (definitions.id IS NOT NULL OR definitions2.id IS NOT NULL)
GROUP BY calls.id
SQL;
    }
}

?>
