<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Exceptions\UnableToReachGraphServer;
use Exakat\Exceptions\Neo4jException;
use Exakat\Exceptions\GremlinException;
use Exakat\Tasks\Tasks;
use Brightzone\GremlinDriver\Connection;
use Brightzone\GremlinDriver\Message;
use Exakat\Graph\Helpers\Websocket as Client;

class Tinkergraph extends Graph {
    const CHECKED = true;
    const UNCHECKED = false;
    
    private $client = null;
    
    private $scriptDir  = '';
    private $neo4j_host = '';
    private $neo4j_auth = '';
    
    private $status     = self::UNCHECKED;
    
    private $log        = null;
    
    public function __construct($config) {
        parent::__construct($config);

        $this->db = new Connection([ 'host'  => $this->config->tinkergraph_host,
                                     'port'  => $this->config->tinkergraph_port,
                                     'graph' => 'graph'
                                   ]);
    }
    
    private function checkConfiguration() {
        $this->db->open();
        $this->db->timeout = 1200;
    }

    public function query($query, $params = array(), $load = array()) {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        assert(is_array($params), 'Params must be an array, '.gettype($params).' provided.'.PHP_EOL.$query);
        foreach($params as $name => $value) {
            $this->db->message->bindValue($name, $value);
        }
        $result = $this->db->send($query);
        
        if (empty($result)) {
            return $result;
        }

        if(is_array($result[0])) {
            foreach($result as &$r) {
                $r = (object) $r;
                if (isset($r->properties)) {
                    foreach($r->properties as $k => &$v) {
                        $v[0] = (object) $v[0];
                    }
                }
                if (isset($r->processed)) {
                    $x = new \stdClass;
                    $x->{1} = isset($r->processed[1]) ? $r->processed[1] : 0;
                    $r->processed = $x;

                    $x = new \stdClass;
                    $x->{1} = isset($r->total[1]) ? $r->total[1] : 0;
                    $r->total = $x;
                }

            }
        }

        return $result;
    }
    
    public function queryOne($query, $params = array(), $load = array()) {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        $res = $this->query($query, $params, $load);
        if (!($res instanceof \Stdclass) || !isset($res->results)) {
            throw new GremlinException('Server is not responding');
        }
        
        if (is_array($res->results)) {
            return $res->results[0];
        } else {
            return $res->results;
        }
    }

    public function checkConnection() {
        $res = @stream_socket_client('tcp://' . $this->config->tinkergraph_host .':'.$this->config->tinkergraph_port,
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
        // This is memory only Database
        $this->stop();
        $this->start();
    }
    
    public function start() {
        if (!file_exists($this->config->tinkergraph_folder.'/conf/tinkergraph.yaml')) {
            copy( $this->config->dir_root.'/server/tinkergraph/conf/tinkergraph.yaml',
                  $this->config->tinkergraph_folder.'/conf/tinkergraph.yaml');
        }

        exec('cd '.$this->config->tinkergraph_folder.'; rm -f db/tinkergraph; bin/gremlin-server.sh conf/tinkergraph.yaml >/dev/null 2>&1 & echo $! > db/tinkergraph.pid ');
        sleep(1);
        
        $b = microtime(true);
        $round = -1;
        do {
            $res = $this->checkConnection();
            ++$round;
            usleep(100000 * $round);
        } while (empty($res));
        $e = microtime(true);

        $pid = trim(file_get_contents($this->config->tinkergraph_folder.'/db/tinkergraph.pid'));
        display('started ['.$pid.'] in '.number_format(($e - $b) * 1000, 2).' ms' );
    }

    public function stop() {
        if (file_exists($this->config->tinkergraph_folder.'/db/tinkergraph.pid')) {
            shell_exec('kill -9 $(cat '.$this->config->tinkergraph_folder.'/db/tinkergraph.pid) 2>>/dev/null; rm -f '.$this->config->tinkergraph_folder.'/db/tinkergraph.pid');
        }
    }
}

?>
