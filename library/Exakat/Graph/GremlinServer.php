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

namespace Exakat\Graph;

use Exakat\Graph\Graph;
use Brightzone\GremlinDriver\Connection as Server;

class GremlinServer extends Graph {
    private $gremlinServer = '';
    
    public function __construct($config) {
        parent::__construct($config);
        
        $this->gremlinServer = new Server([
   'host' => 'localhost',
   'graph' => 'graph'
]);
        $this->gremlinServer->open();
    }

    public function query($query, $params = array(), $load = array()) {
        //$result = $db->send('g.addV("name", "PHP")'); //result = [10]
        try {
//            $query = addslashes($query);
//            $query = str_replace("\n", " ", $query);
            print $query."\n";
            $result = $this->gremlinServer->send($query); //result = [10]
        } catch( \Brightzone\GremlinDriver\ServerException $e) {
            // Do nothing
            print "Catch exception\n";
            print $e->getMessage();

            $result = array();
        } 
        print $query;
        print_r($result);
//        die(GREMLIN);
    }

    public function queryOne($query, $params = array(), $load = array()) {}

    public function serverInfo() {}
    
    public function queryCypher($query) {
        //$result = $db->send('g.addV("name", "PHP")'); //result = [10]
        try {
            $query = addslashes($query);
            $query = str_replace("\n", " ", $query);
            $result = $this->gremlinServer->send('graph.cypher("'.$query.'");'); //result = [10]
        } catch( \Brightzone\GremlinDriver\ServerException $e) {
            // Do nothing
            print "Catch exception\n";
            print $e->getMessage();

            $result = array();
        } 
   }
}

?>
