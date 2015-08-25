<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
    static $config;
    
    if ($config === null) {
        $config = \Config::factory();
    }
    
    if ($config->verbose) {
        echo trim($text)."\n";
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

function gremlin_query($query, $params = [], $load = '') {
    if (!defined('GREMLIN_QUERY')) {
        // Define the GREMLIN_QUERY constant
        $config = \Config::factory();
        $json = file_get_contents('http://'.$config->neo4j_host.':'.$config->neo4j_port.'/db/data/');

        if (empty($json)) {
            define('GREMLIN_QUERY', 'gremlin_queryA');
        } else {
            $json = json_decode($json);
            if (isset($json->extensions->GremlinPlugin)) {
                define('GREMLIN_QUERY', 'gremlin_queryN');
            } else {
                define('GREMLIN_QUERY', 'gremlin_queryA');
            }
        }
    }
    
    if (GREMLIN_QUERY == 'gremlin_queryN') {
        return gremlin_queryN($query, $params);
    } elseif (GREMLIN_QUERY == 'gremlin_queryA') {
        return gremlin_queryA($query, $params, $load);
    } else {
        throw new \Exception('Couldn\'t find Gremlin');
    }
}

function gremlin_queryA($query, $params = [], $load = '') {
    static $loadedScripts = [];

    $getString = 'script='.urlencode($query);
    
    if (isset($params) && !empty($params)) {
        foreach($params as $name => $value) {
            if (is_string($value) && strlen($value) > 2000) {
                $gremlin = '{ "'.addslashes($value).'" }';

                // what about factorise this below? 
                $defName = 'a'.crc32($gremlin);
                $defFileName = 'neo4j/scripts/'.$defName.'.gremlin';

                if (isset($loadedScripts[$defName])) {
                    $query = str_replace($name, $defName.'()', $query);
                    $getString = 'script='.urlencode($query);
                    unset($params[$name]);

                } elseif (file_exists($defFileName)) {
                    $query = str_replace($name, $defName.'()', $query);

                    $getString = 'script='.urlencode($query).'&load='.$defName;
                    unset($params[$name]);
                    
                    $loadedScripts[$defName] = true;
                } else {
                    $gremlin = 'def '.$defName.'() '.$gremlin;
                    file_put_contents($defFileName, $gremlin);

                    $query = str_replace($name, $defName.'()', $query);

                    $getString = 'script='.urlencode($query).'&load='.$defName;
                    unset($params[$name]);

                    $loadedScripts[$defName] = true;
                }
            } elseif (is_array($value) && strlen(join('', $value)) > 2000) {
                $value = array_map('addslashes', $value);
                $gremlin = '{ ["'.join('","', $value).'"] }';
                $defName = 'a'.crc32($gremlin);
                $defFileName = 'neo4j/scripts/'.$defName.'.gremlin';

                if (isset($loadedScripts[$defName])) {
                    $query = str_replace($name, $defName.'()', $query);
                    $getString = 'script='.urlencode($query);
                    unset($params[$name]);

                } elseif (file_exists($defFileName)) {
                    $query = str_replace($name, $defName.'()', $query);

                    $getString = 'script='.urlencode($query).'&load='.$defName;
                    unset($params[$name]);
                    
                    $loadedScripts[$defName] = true;
                } else {
                    $gremlin = 'def '.$defName.'() '.$gremlin;
                    file_put_contents($defFileName, $gremlin);

                    $query = str_replace($name, $defName.'()', $query);

                    $getString = 'script='.urlencode($query).'&load='.$defName;
                    unset($params[$name]);

                    $loadedScripts[$defName] = true;
                }
            } elseif (is_array($value)) { 
            // all the other arrays, we hardcode them

                // needed double encoding for protecting \
                foreach($value as &$v) {
                    $v = str_replace(array('\\', '"', "'", '$'), array('\\\\\\\\', '\\"', "\\'", '\\\\$'), $v); // double encoding for Gremlin, plus a third for PHP string.
                }
                (unset) $v;

                $gremlinArray = '["'.join('", "', $value).'"]';
                $query = preg_replace('/'.$name.'/is', $gremlinArray, $query);
                (unset) $params[$name];

                $getString = 'script='.urlencode($query);

            } // else is not an array
        }

        if (!empty($params)) {
            $getString .= '&params='.urlencode(json_encode($params));
        }
    }

    if (isset($load) && !empty($load)) {
        $getString .= '&load='.urlencode($load);
    }
    
    if (strlen($getString) > 20000) {
        print "Query string too big for GET (".strlen($getString).")\n";
        print "Query : ";
        print($query);
        print "\n\n";
        print_r($params);
        die();
    }

    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_HTTPHEADER, array(
                    'Content-Length: '.strlen($getString),
                    'User-Agent: exakat',
                    'X-Stream: true'
                ));

    curl_setopt($ch,CURLOPT_URL, 'http://127.0.0.1:7474/tp/gremlin/execute?'.$getString);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'GET');
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
    
    if (isset($result->message)) {
        throw new \Exception($result->message);
    }

    return json_decode($result);
}


function gremlin_queryN($query, $params = [], $load = '') {
    $fields = ['script' => $query];
    if (isset($params) && !empty($params)) {
        $fields['params'] = $params;
    }

    $fields_string = json_encode($fields);

    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_HTTPHEADER, array(
    				'Accept: application/json;stream=true',
    				'Content-type: application/json',
                    'Content-Length: '.strlen($fields_string),
                    'User-Agent: exakat',
                    'X-Stream: true'
                ));
                //''
    curl_setopt($ch,CURLOPT_URL, 'http://127.0.0.1:7474/db/data/ext/GremlinPlugin/graphdb/execute_script');
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
//    curl_setopt($ch,CURLOPT_HEADER,true);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

    if (isset($result->message)) {
        throw new \Exception($result->message);
    }

    return json_decode($result);
}

function gremlin_queryOne($query, $params = [], $load = '') {
    $res = gremlin_query($query, $params, $load );
    $res = $res->results[0];
    
    if (is_bool($res) || is_int($res)) {
        return $res;
    } else {
        print "Help needed in ".__METHOD__."\n";
        print "Query : '$query'\n";
        var_dump($res);
        die();
    }
}

function gremlin_queryColumn($query, $params = [], $load = '') {
    $res = gremlin_query($query, $params, $load );
    $res = $res->results;
    
    $return = array();
    if(count($res) > 0) {
        foreach($res as $r) {
            $return[] = $r;
        }
    }
    
    return $return;
}

function cypher_query($query, $params = []) {
    $fields = ['query' => $query];
    if (isset($params) && !empty($params)) {
        $fields['params'] = $params;
    }

    $fields_string = json_encode($fields);

    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_HTTPHEADER, array(
    				'Accept: application/json;stream=true',
    				'Content-type: application/json',
                    'Content-Length: '.strlen($fields_string),
//                    'User-Agent: exakat',
//                    'X-Stream: true'
                ));

    curl_setopt($ch,CURLOPT_URL, 'http://localhost:7474/db/data/cypher');
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

    if (isset($result->message)) {
        throw new \Exception($result->message);
    }

    return json_decode($result);
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

?>