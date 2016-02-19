<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


function display($text) {
    $config = \Config::factory();
    
    if ($config->verbose) {
        echo trim($text), "\n";
    }
}

function display_r($object) {
    static $config;
    
    if ($config === null) {
        $config = \Config::factory();
    }
    
    if ($config->verbose) {
        print_r( $object );
    }
}

function gremlin_query($query, $params = [], $load = []) {
    static $scriptDir;
    
    if (!isset($scriptDir)) {
        $config = \Config::factory();
        $scriptDir = $config->neo4j_folder.'/scripts/';

        if (!is_writable($scriptDir)) {
            die("Can't write in file $scriptDir. Exakat needs to write in this folder.");
        }
    }
    $getString = 'script='.urlencode($query);
    
    if (!is_array($load)) {
        $load = [$load];
    }

    if (isset($params) && !empty($params)) {
        // Avoid changing arg10 to 'string'0 if query has more than 10 arguments.
        krsort($params);

        foreach($params as $name => $value) {
            if (is_string($value) && strlen($value) > 2000) {
                $gremlin = "{ '".str_replace('$', '\\$', $value)."' }";

                // what about factorise this below? 
                $defName = 'a'.crc32($gremlin);
                $defFileName = $scriptDir.$defName.'.gremlin';

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
                $value = array_map(function ($x) { return str_replace('$', '\\$', addslashes($x)); }, $value);
                $gremlin = "{ ['".join("','", $value)."'] }";
                $defName = 'a'.crc32($gremlin);
                $defFileName = $scriptDir.$defName.'.gremlin';

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
            } else { // a short string (less than 2000) : hardcoded
                $query = str_replace($name, "'".addslashes($value)."'", $query);
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
    
    if (strlen($getString) > 20000) {
        echo 'Query string too big for GET (', strlen($getString), ")\n",
             'Query : ',
             $query,
            "\n\n",
            print_r($params, true);
        die();
    }

    $ch = curl_init();

    static $neo4j_host, $neo4j_auth;
    if (!isset($neo4j_host)) {
        $config = \Config::factory();
        $neo4j_host   = $config->neo4j_host.':'.$config->neo4j_port;

        if ($config->neo4j_login !== '') {
            $neo4j_auth   = base64_encode($config->neo4j_login.':'.$config->neo4j_password);
        } else {
            $neo4j_auth   = '';
        }
    }

    //set the url, number of POST vars, POST data
    $headers = array( 'Content-Length: '.strlen($getString),
                      'User-Agent: exakat',
                      'X-Stream: true');
    if (!empty($neo4j_auth)) {
        $headers[] = 'Authorization: Basic '.$neo4j_auth;
    }
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch,CURLOPT_URL,            'http://'.$neo4j_host.'/tp/gremlin/execute?'.$getString);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,  'GET');
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_IPRESOLVE,      CURL_IPRESOLVE_V4);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
    
    $result = json_decode($result);
    if (isset($result->errormessage)) {
        throw new \Exceptions\GremlinException($result->errormessage, $query);
    }

    return $result;
}


function gremlin_queryOne($query, $params = [], $load = []) {
    $res = gremlin_query($query, $params, $load);
    if (!is_object($res)) {
        die('Server is not responding');
    }
    $res = $res->results[0];
    
    if (is_bool($res) || is_int($res)) {
        return $res;
    } else {
        echo 'Help needed in ', __METHOD__, "\n",
             "Query : '", $query, "'\n",
             print_r($res, true);
        die();
    }
}

function gremlin_queryColumn($query, $params = [], $load = []) {
    $res = gremlin_query($query, $params, $load);
    $res = $res->results;
    
    $return = array();
    if(count($res) > 0) {
        foreach($res as $r) {
            $return[] = $r;
        }
    }
    
    return $return;
}

function neo4j_serverInfo() {
    static $config;
    
    if ($config === null) {
        $config = \Config::factory();
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://'.$config->neo4j_host);
    curl_setopt($ch, CURLOPT_PORT, $config->neo4j_port);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}

function rmdirRecursive($dir) { 
    if (!file_exists($dir)) { 
        // Do nothing
        return 0;
    }

    // Remove symlink, but not their content
    if (is_link($dir)) {
        unlink($dir);
        return 0;
    }

    if (empty($dir)) { 
        return 0;
    }
    
    $total = 0;
    $files = array_diff(scandir($dir), array('.','..')); 
    
    foreach ($files as $file) { 
        if (is_dir("$dir/$file")) {
            $total += rmdirRecursive("$dir/$file");
        } else {
            unlink("$dir/$file"); 
            ++$total;
        }
    } 

    rmdir($dir);
    ++$total;

    return $total; 
  } 

function copyDir($src, $dst) { 
    $dir = opendir($src); 
    if (!$dir) { return true; }
    
    $total = 0;
    mkdir($dst, 0755); 
    while(false !==  $file = readdir($dir) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                $total += copyDir($src . '/' . $file,$dst . '/' . $file); 
            } else { 
                copy($src . '/' . $file, $dst . '/' . $file); 
                ++$total;
            } 
        } 
    } 
    
    closedir($dir); 

    return $total;
} 

?>