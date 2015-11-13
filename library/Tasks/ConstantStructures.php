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


namespace Tasks;

class ConstantStructures extends Tasks {
    private $lastTiming = 0;
    
    public function run(\Config $config) {
        $this->displayTiming('Start');

        // First, clean it all
        $query = 'g.V.hasNot("constante", null).each{ it.removeProperty("constante")};';
        $this->query($query);
        $this->displayTiming('Initial clean');

        // Case for Literals
//        $literals = array('Integer', 'Boolean', 'Float', 'Null', 'Void');
        $literals = array('Integer', 'Boolean', 'Float', 'Null');
        $literals = array( 'Void');
//        $literals = array();
        foreach($literals as $literal) {
            print $query = 'g.idx("atoms")[["atom":"'.$literal.'"]].each{ it.setProperty("constante", true)};';
            $this->query($query);
            $this->displayTiming($literal);
        }
/*    
        // String that are concatenations are differente
        $query = 'g.idx("atoms")[["atom":"String"]].filter{it.out("CONTAINS").any() == false}.each{ it.setProperty("constante", true)};';
        $this->query($query);
        $this->displayTiming('String/concatenations');

        for ($i =0; $i < 3; ++$i) {
        // Cases for Structures (all sub element are constante => structure is constante)
        $structures = array('Addition'       => array('LEFT', 'RIGHT'),
                            'Multiplication' => array('LEFT', 'RIGHT'),
                            'Keyvalue'       => array('KEY',  'VALUE'),
                            'Arguments'      => array('ARGUMENT'),
                            'Sequence'       => array('ELEMENT'),
                            'Break'          => array('BREAK'),
                            'Continue'       => array('CONTINUE'),
                            'Return'         => array('RETURN'),
                            );
        
        foreach($structures as $atom => $links) {
            $linksList = "'".implode("', '", $links)."'";

            $query = <<<GREMLIN
g.idx("atoms")[["atom":"$atom"]]
    .hasNot('constante', true)
    .filter{ it.out($linksList).hasNot("constante", true).any() == false}
    .each{ it.setProperty("constante", true);}
GREMLIN;
            $this->query($query);
            $this->displayTiming($atom);
        }
        
        // case for Arguments
        $query = <<<GREMLIN
g.idx("atoms")[["atom":"Functioncall"]]
    .out('ARGUMENTS')
    .hasNot('constante', true)
    .filter{ it.out('ARGUMENT').hasNot("constante", true).any() == false}
    .each{ it.setProperty("constante", true);}
GREMLIN;
        $this->query($query);
        $this->displayTiming('Arguments');

        // case for Assignation
        $query = <<<GREMLIN
g.idx("atoms")[["atom":"Assignation"]]
    .has('code', '=')
    .filter{ it.out("RIGHT").has("constante", true).any()}
    .sideEffect{ it.setProperty("constante", true); }
    .out('LEFT')
    .each{ it.setProperty("constante", true);}
GREMLIN;
        $this->query($query);
        $this->displayTiming('Assignation');

        // case for array
        $query = <<<GREMLIN
g.idx("atoms")[["atom":"Functioncall"]]
    .has('token', 'T_ARRAY')
    .filter{ it.out("ARGUMENTS").has("constante", true).any()}
    .each{ it.setProperty("constante", true);}
GREMLIN;
        $this->query($query);
        $this->displayTiming('Array');
        }

*/
        // Final count
        $query = <<<GREMLIN
g.V.count();
GREMLIN;
        $vertices = $this->query($query);
        $total = $vertices[0];
        display( 'Total tokens : '.$total." \n");

        $query = <<<GREMLIN
g.V.has('constante', true).count();
GREMLIN;
        $vertices = $this->query($query);
        $constantes = $vertices[0];
        display( 'Constante tokens : '.$constantes." (".number_format($constantes / $total * 100, 2)."% )\n");

    }

    private function displayTiming($message) {
        display( $message."\nTime : ".number_format($this->lastTiming * 1000, 0)."ms\n");
    }
    
    private function query($query, $retry = 1) {
        $this->lastTiming = 0;
        $begin = microtime(true);

        $res = gremlin_query($query);
        $res = $res->results;

        $this->lastTiming = $begin - time();

        return $res;
    }
}

?>
