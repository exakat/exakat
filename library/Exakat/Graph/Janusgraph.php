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
use Exakat\Exceptions\GremlinException;
use Exakat\Tasks\Tasks;
use Exakat\Graph\Helpers\Websocket as Client;
use stdClass;

class Janusgraph extends Graph {
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
        
        $this->client = new Client('ws://'.$this->config->janusgraph_host.':'.$this->config->janusgraph_port.'/',
                                    array('timeout'       => 123,
                                          'fragment_size' => 1024 * 1024));
    }

    public function query($query, $params = array(), $load = array()) {
        $query = preg_replace('#g.V\(\).hasLabel\((.+?)\)#s', 'g.V().has("atom", within(\1))', $query);

        $message = array(
                'requestId' => self::createUuid(),
                'op'        => 'eval',
                'processor' => '',
                'args'      => array('gremlin' => $query)
                );
        if (!empty($params)) {
            $message['args']['bindings'] = $params;
        }
        $message = json_encode($message);
        $mimeType = 'application/json';
        $finalMessage = pack('c', strlen($mimeType)) . $mimeType . $message;

        $this->client->send($finalMessage, 'binary');

        $final = array();
        $round = 1;
        do {
            $res = $this->client->receive();
            $result = json_decode($res);
            ++$round;
            $final[] = is_array($result->result->data) ? $result->result->data : array();
        } while($result->status->code == 206);
        
        $final = call_user_func_array('array_merge', $final);

        // We reuse the final result obtained.
        $result->results = &$final;
        
        return $result;
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

    public function serverInfo() {
        if ($this->status === self::UNCHECKED) {
//            $this->checkConfiguration();
        }
        
        $res = $this->query('Gremlin.version();');

        return $res;
    }
    
    private function createUuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    public function checkConnection() {
        $res = @stream_socket_client('tcp://' . $this->config->janusgraph_host .':'.$this->config->janusgraph_port,
                                     $errno,
                                     $errorMessage,
                                     1,
                                     STREAM_CLIENT_CONNECT
                                     );

        return is_resource($res);
    }
    
    public function clean() {
        $this->stop();
        $this->start();
    }
    
    public function start() {
        if (!file_exists($this->config->janusgraph_folder.'/conf/gremlin-server/exakat.yaml')) {
            copy( $this->config->dir_root.'/server/janusgraph/conf//gremlin-server/exakat.yaml',
                  $this->config->janusgraph_folder.'/conf/gremlin-server/exakat.yaml');
        }

        exec('cd '.$this->config->janusgraph_folder.'; rm -rf db/berkeley;  sh ./bin/gremlin-server.sh conf/gremlin-server/exakat.yaml  >/dev/null 2>&1 & echo $! > db/janus.pid ');
        sleep(1);
        
        // Might be : Another server-process is running with [49633], cannot start a new one. Exiting.
        // Needs to pick up this error and act
        // also, may be we can wait for the pid to appear?
        $round = 0;
        $res = null;
        $round = -1;
        do {
            $res = $this->checkConnection();
            ++$round;
            usleep(100000 * $round);
        } while (empty($res));
        
        if ($round >= 10) {
            die( 'Not able to start Janusgraph. Please, check your installation'.PHP_EOL);
        } else {
            display('Janusgraph restarted');
        }
    }

    public function stop() {
        if (file_exists($this->config->janusgraph_folder.'/db/janus.pid')) {
            shell_exec('cat '.$this->config->janusgraph_folder.'/db/janus.pid | xargs kill ');
        }
    }

    public function getDefinitionSQL() {}
}

?>
