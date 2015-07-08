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
    $getString = 'script='.urlencode($query);

    if (isset($params) && !empty($params)) {
        $getString .= '&params='.urlencode(json_encode($params));
    }

    if (isset($load) && !empty($load)) {
        $getString .= '&load='.urlencode($load);
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

    return json_decode($result);
}

function gremlin_queryOne($query, $params = [], $load = '') {
    $res = gremlin_query($query, $params, $load );
    
    if (is_bool($res) || is_int($res)) {
        return $res;
    } else {
        print "Help needed in ".__METHOD__."\n";
        var_dump($res);
        die();
        return $res[0];
    }
}

?>