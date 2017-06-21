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

class Gremlin3 extends Graph {
    const CHECKED = true;
    const UNCHECKED = false;
    
    private $scriptDir  = '';
    private $neo4j_host = '';
    private $neo4j_auth = '';
    
    private $status     = self::UNCHECKED;
    
    private $log        = null;
    
    public function __construct($config) {
        parent::__construct($config);
        
        $this->scriptDir = $config->neo4j_folder.'/scripts/';

        $this->neo4j_host   = $config->neo4j_host.':'.$config->neo4j_port;

        if ($this->config->neo4j_login !== '') {
            $this->neo4j_auth   = base64_encode($this->config->neo4j_login.':'.$this->config->neo4j_password);
        }
        
        if ($config->project != 'default' && $config->project != 'test' &&
            file_exists($config->projects_root.'/projects/'.$config->project)) {
            $this->log = fopen($config->projects_root.'/projects/'.$config->project.'/log/gremlin.log', 'a');
            fwrite($this->log, "New connexion \n");
        }
    }
    
    public function __destruct() {
        if ($this->log !== null) {
            fwrite($this->log, "End connexion \n");
            fclose($this->log);
        }
    }
    
    private function checkConfiguration() {
        if (!file_exists($this->config->neo4j_folder)) {
            throw new Neo4jException("Error in the path to the Neo4j folder (".$this->config->neo4j_folder."). Please, check config/exakat.ini\n");
        }

        if (!file_exists($this->scriptDir)) {
            mkdir($this->scriptDir, 0755);
        } elseif (!is_writable($this->scriptDir)) {
            throw new Neo4jException("Can't write in '$this->scriptDir'. Exakat needs to write in this folder.\n");
        }
        
        $this->status = self::CHECKED;
    }

    public function query($query, $params = array(), $load = array()) {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        $getString = 'script='.urlencode($query);
    
        if (!is_array($load)) {
            $load = array($load);
        }

        if (isset($params) && !empty($params)) {
            // Avoid changing arg10 to 'string'0 if query has more than 10 arguments.
            krsort($params);
            
            foreach($params as $name => $value) {
                if (is_string($value) && strlen($value) > 2000) {
                    $gremlin = "{ '".str_replace('$', '\\$', $value)."' }";

                    // what about factorise this below? 
                    $defName = 'a'.dechex(crc32($gremlin));
                    $defFileName = $this->scriptDir.$defName.'.gremlin';

                    if (file_exists($defFileName)) {
                        $query = str_replace($name, $defName.'()', $query);

                        $load[] = $defName;
                        unset($params[$name]);
                    } else {
                        $gremlin = 'def '.$defName.'() '.$gremlin;
                        file_put_contents($defFileName, $gremlin);

                        $query = str_replace($name, $defName.'()', $query);

                        $load[] = $defName;
                        unset($params[$name]);
                    }
                } elseif (is_array($value)) {
                    $gremlin = $this->toGremlin($value);
                    $defName = 'a'.dechex(crc32($gremlin));
                    $defFileName = $this->scriptDir.$defName.'.gremlin';

                    if (file_exists($defFileName)) {
                        $query = str_replace($name, $defName.'()', $query);

                        $load[] = $defName;
                        unset($params[$name]);
                    } else {
                        $gremlin = 'def '.$defName.'() '.$gremlin;
                        if (strlen($gremlin) > 65535 ) {
                            $gremlin = <<<GREMLIN
def $defName() { 
    x = [];
    dir = new File("scripts").absolutePath;
    new File(dir + "/$defName.txt").each({ line -> x.push(line)});
    x; 
}
GREMLIN;
                            file_put_contents($defFileName, $gremlin);
                            file_put_contents($this->scriptDir.$defName.'.txt', $this->toGremlinTxt($value) );
                        } else {
                            file_put_contents($defFileName, $gremlin);
                        }

                        $query = str_replace($name, $defName.'()', $query);

                        $load[] = $defName;
                        unset($params[$name]);
                    }
                } else { // a short string (less than 2000) : hardcoded
                    $query = str_replace($name, "'''".addslashes($value)."'''", $query);
                    unset($params[$name]);
                }
            }

            if (!empty($params)) {
                $getString .= '&params='.urlencode(json_encode($params));
            }
        }

        $getString = 'script='.urlencode($query);

        if (count($load) == 1) {
            $getString .= '&load='.urlencode(array_pop($load));
        } elseif (count($load) > 1) {
            $getString .= '&load='.implode(',', array_map('urlencode', $load));
        } // else (aka 0) is ignored (nothing to do)
    
        assert(strlen($getString) < 20000,  
              'Query string too big for GET ('. strlen($getString). ")\n" . 'Query : ' . $query . "\n\n" . print_r($params, true));
        if (strlen($getString) > 20000) {
            return array();
        }

        $ch = curl_init();

        //set the url, number of POST vars, POST data
        $headers = array('User-Agent: exakat',
                          'X-Stream: true');
        if (!empty($this->neo4j_auth)) {
            $headers[] = 'Authorization: Basic '.$this->neo4j_auth;
        }
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch,CURLOPT_URL,            'http://'.$this->neo4j_host.'/tp/gremlin/execute?'.$getString);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,  'GET');
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_IPRESOLVE,      CURL_IPRESOLVE_V4);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);
    
        $result = json_decode($result);
        if (isset($result->errormessage)) {
            if ($this->log !== null) {
                fwrite($this->log, $query."\n".$result->errormessage."\n");
            }
            throw new GremlinException($result->errormessage, $query);
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

    public function serverInfo() {
        if ($this->status === self::UNCHECKED) {
            $this->checkConfiguration();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://'.$this->config->neo4j_host);
        curl_setopt($ch, CURLOPT_PORT, $this->config->neo4j_port);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
    
    private function toGremlin($array) {
        if (empty($array)) {
            return "{ [  ] }";
        }
        $keys = array_keys($array);
        $key = $keys[0];
        if (is_array($array[$key])) {
            $gremlin = array();
            foreach($array as $key => $value) {
                $a = array_map(function ($x) { return addslashes($x); }, $value);
                $gremlin[] = "'''".addslashes($key)."''':['''".implode("''','''", $a)."''']";
            }
            $gremlin = "{ [" . implode(', ', $gremlin). "] }"; 
        } elseif (is_object($array[$key])) {
            $gremlin = array();
            foreach($array as $key => $value) {
                $a = array_map(function ($x) { return addslashes($x); }, (array) $value);
                $gremlin[] = "'''".addslashes($key)."''':['''".implode("''','''", $a)."''']";
            }
            $gremlin = "{ [" . implode(', ', $gremlin). "] }"; 
        } else {
            $array = array_map(function ($x) { return addslashes($x); }, $array);
            $gremlin = "{ ['''".implode("''','''", $array)."'''] }";
        }
        
        return $gremlin;
    }

    private function toGremlinTxt($array) {
        $keys = array_keys($array);
        $key = $keys[0];
        
        if (is_array($array[$key])) {
            $gremlin = array();
            foreach($array as $key => $value) {
                $a = array_map(function ($x) { return addslashes($x); }, $value);
                $gremlin[] = "['''".implode("''','''", $a)."''']";
            }
            $gremlin = implode("\n", $gremlin); 
        } else {
            $gremlin = implode("\n", $array);
        }
        
        return $gremlin;
    }
    
    public function cleanWithRestart() {
        display('Cleaning with restart');
        $this->cleanScripts();

        // preserve data/dbms/auth to preserve authentication
        if (file_exists($this->config->neo4j_folder.'/data/dbms/auth')) {
            $sshLoad =  'mv data/dbms/auth ../auth; rm -rf data; mkdir -p data/dbms; mv ../auth data/dbms/auth; mkdir -p data/log; mkdir -p data/scripts ';
        } else {
            $sshLoad =  'rm -rf data; mkdir -p data; mkdir -p data/log; mkdir -p data/scripts ';
        }

        // if neo4j-service.pid exists, we kill the process once
        if (file_exists($this->config->neo4j_folder.'/data/neo4j-service.pid')) {
            shell_exec('kill -9 $(cat '.$this->config->neo4j_folder.'/data/neo4j-service.pid) 2>>/dev/null; ');
        }

        shell_exec('cd '.$this->config->neo4j_folder.'; '.$sshLoad);

        if (!file_exists($this->config->neo4j_folder.'/conf/')) {
            print "No conf folder in {$this->config->neo4j_folder}\n";
        } elseif (!file_exists($this->config->neo4j_folder.'/conf/neo4j-server.properties')) {
            print "No neo4j-server.properties file in {$this->config->neo4j_folder}/conf/\n";
        } else {
            $neo4j_config = file_get_contents($this->config->neo4j_folder.'/conf/neo4j-server.properties');
            if (preg_match('/org.neo4j.server.webserver.port *= *(\d+)/m', $neo4j_config, $r)) {
                if ($r[1] != $this->config->neo4j_port) {
                    print "Warning : Exakat's port and Neo4j's port are not the same ($r[1] / {$this->config->neo4j_port})\n";
                }
            }
        }

        // checking that the server has indeed restarted
        if (Tasks::$semaphore !== null) {
            fclose(Tasks::$semaphore);
            $this->doRestart();
            Tasks::$semaphore = @stream_socket_server("udp://0.0.0.0:".Tasks::$semaphorePort, $errno, $errstr, STREAM_SERVER_BIND);
        } else {
            $this->doRestart();
        }

        display('Database cleaned with restart');

        try {
            $res = $this->serverInfo();
            display('Restarted Neo4j cleanly');
        } catch (Exception $e) {
            display('Didn\'t restart neo4j cleanly');
        }
    }

    private function doRestart() {
        $round = 0;
        do {
            ++$round;
            if ($round > 0) {
                sleep($round);
            }

            if ($round > 10) {
                if (file_exists($this->config->neo4j_folder.'/data/neo4j-service.pid')) {
                    $pid = file_get_contents($this->config->neo4j_folder.'/data/neo4j-service.pid');
                    die('Couldn\'t restart neo4j\'s server. Please, kill it (kill -9 '.$pid.') and try again');
                } else {
                    die('Couldn\'t restart neo4j\'s server, though it doesn\'t seem to be running. Please, make sure it is runnable at "'.$this->config->neo4j_folder.'" and try again.');
                }
            }

            echo exec('cd '.$this->config->neo4j_folder.'; ./bin/neo4j start >/dev/null 2>&1 & ');

            // Might be : Another server-process is running with [49633], cannot start a new one. Exiting.
            // Needs to pick up this error and act
            // also, may be we can wait for the pid to appear?

            $res = $this->serverInfo();
        } while ( $res === false);
    }

    public function cleanScripts() {
        display('Cleaning scripts');
        $aGremlin = glob($this->config->neo4j_folder.'/scripts/a*.gremlin');
        $aTxt = glob($this->config->neo4j_folder.'/scripts/a*.txt');
        
        $aTxt = empty($aTxt) ? array() : $aTxt;
        $aGremlin = empty($aGremlin) ? array() : $aGremlin;
        
        $files = array_merge($aGremlin, $aTxt);
        foreach($files as $file) {
            unlink($file);
        }
        display('   Cleaned '.count($files).' gremlin scripts');
    }
    
    public function cleanDatabase() {
        $this->cleanScripts();

        $queryTemplate = <<<GREMLIN
g.V().count();
GREMLIN;
        $result = null;
        $counts = 0;

        while($counts < 100 && (!$result instanceof \Stdclass || $result->results === null)) {
            $result = $this->query($queryTemplate);
            ++$counts;
            usleep(100000);
        }

        if ($counts === 100)  {
            display('No connexion to gremlin : forcing restart ('.$counts.')');
            // Can't connect to gremlin. Forcing restart.
            $this->restart();
            $this->cleanScripts();
            return ;
        } else {
            display('Connexion to gremlin (Neo4j) found ('.$counts.')');
        }
        $nodes = $result->results[0];
        display($nodes.' nodes in the database');

        $begin = microtime(true);
        if ($nodes == 0) {
            display('No nodes in Gremlin. No need to clean');
        } elseif ($nodes > 10000) {
            display($nodes.'nodes : forcing restart');
            $this->cleanWithRestart();
        } else {
            display('Cleaning with gremlin');

            $queryTemplate = <<<GREMLIN

g.E().drop();
g.V().drop();

GREMLIN;
            $this->query($queryTemplate);
            display('Database cleaned');
        }
        $end = microtime(true);
        display(number_format(($end - $begin) * 1000, 0).' ms');
    }
}

?>
