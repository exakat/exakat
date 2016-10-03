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

namespace Graph;

class Cypher extends Graph {
    public function __construct(\Exakat\Config $config) {
        parent::__construct($config);
    }

    public function query($query, $params = [], $load = []) {
        $fields = ['query' => $query];
        if (isset($params) && !empty($params)) {
            $fields['params'] = $params;
        }

        $fields_string = json_encode($fields);

        $ch = curl_init();

        static $neo4j_host, $neo4j_auth;
        if (!isset($neo4j_host)) {  
            $config = \Exakat\Config::factory();
            $neo4j_host = $config->neo4j_host.':'.$config->neo4j_port;
        
            if ($config->neo4j_login !== '') {
                $neo4j_auth   = base64_encode($config->neo4j_login.':'.$config->neo4j_password);
            } else {
                $neo4j_auth   = '';
            }
        }

        //set the url, number of POST vars, POST data
        $headers = array( 'Accept: application/json;stream=true',
                          'Content-type: application/json',
                          'Content-Length: '.strlen($fields_string));
        if (!empty($neo4j_auth)) {
            $headers[] = 'Authorization: Basic '.$neo4j_auth;
        }
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch,CURLOPT_URL, 'http://'.$neo4j_host.'/db/data/cypher');
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
}

?>
