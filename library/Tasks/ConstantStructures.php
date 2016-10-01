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


namespace Tasks;

class ConstantStructures extends Tasks {
    private $lastTiming = 0;
    
    public function run(\Exakat\Config $config) {
        $this->displayTiming('Start');

        // First, clean it all
        $query = 'g.V.has("constante", true).each{ it.removeProperty("constante")};';
        $this->gremlin->query($query);
        $this->displayTiming('Initial clean');

        // Case for Literals
        $literals = array('Integer', 'Boolean', 'Real', 'Null', 'Void', 'RawString', 'Magicconstant', 'Staticconstant');
        foreach($literals as $literal) {
            $query = 'g.idx("atoms")[["atom":"'.$literal.'"]].each{ it.setProperty("constante", true)};';
            $this->gremlin->query($query);
            $this->displayTiming($literal);
        }

        // String that are concatenations are special
        $query = 'g.idx("atoms")[["atom":"String"]].filter{it.out("CONTAINS").any() == false}.each{ it.setProperty("constante", true)};';
        $this->gremlin->query($query);
        $this->displayTiming('String/concatenations');

        $constantes = array('Identifier', 'Nsname');
        foreach($constantes as $constant) {
            $query = 'g.idx("atoms")[["atom":"'.$constant.'"]].hasNot("atom", "Functioncall").each{ it.setProperty("constante", true)};';
            $this->gremlin->query($query);
            $this->displayTiming($constant);
        }

        for ($i =0; $i < 3; ++$i) {
        // Cases for Structures (all sub element are constante => structure is constante)
        $structures = array('Addition'         => array('LEFT', 'RIGHT'),
                            'Multiplication'   => array('LEFT', 'RIGHT'),
                            'Bitshift'         => array('LEFT', 'RIGHT'),
                            'Logical'          => array('LEFT', 'RIGHT'),
                            'Keyvalue'         => array('KEY',  'VALUE'),
                            'Arguments'        => array('ARGUMENT'),
                            'Functioncall'     => array('ARGUMENTS'), // Warning : Some function are not deterministic. Needs to mark them as such (custom or internals...)
//                            'Methodcall'       => array('METHOD'),
//                            'Staticmethodcall' => array('METHOD'),

//                            'Function'         => array('BLOCK'),  // Block but one should look for return 
                            'Sequence'         => array('ELEMENT'),
                            'Break'            => array('BREAK'),
                            'Continue'         => array('CONTINUE'),
                            'Return'           => array('RETURN'),
                            'Ternary'          => array('CONDITION', 'THEN', 'ELSE'),
                            'Comparison'       => array('LEFT', 'RIGHT'),
                            'Noscream'         => array('AT'),
                            'Not'              => array('NOT'),
                            'Parenthesis'      => array('CODE'),
                            );
        
        foreach($structures as $atom => $links) {
            $linksList = "'".implode("', '", $links)."'";

            $query = <<<GREMLIN
g.idx("atoms")[["atom":"$atom"]]
    .hasNot('constante', true)
    .filter{ it.out($linksList).hasNot("constante", true).any() == false}
    .each{ it.setProperty("constante", true);}
GREMLIN;
            $this->gremli->query($query);
            $this->displayTiming($atom);
        }

        /*
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
        */

        // case for Assignation
        $query = <<<GREMLIN
g.idx("atoms")[["atom":"Assignation"]]
    .has('code', '=')
    .filter{ it.out("RIGHT").has("constante", true).any()}
    .sideEffect{ it.setProperty("constante", true); }
    .out('LEFT')
    .each{ it.setProperty("constante", true);}
GREMLIN;
        $this->gremlin->query($query);
        $this->displayTiming('Assignation');

        // case for array
        /*
        $query = <<<GREMLIN
g.idx("atoms")[["atom":"Functioncall"]]
    .has('token', 'T_ARRAY')
    .filter{ it.out("ARGUMENTS").has("constante", null).any() == false}
    .each{ it.setProperty("constante", true);}
GREMLIN;
        $this->gremlin->query($query);
        $this->displayTiming('Array');
*/
        }
        // Final count
        $query = <<<GREMLIN
g.V.count();
GREMLIN;
        $vertices = $this->gremlin->query($query);
        $total = $vertices[0];
        display( 'Total tokens : '.$total." \n");

        $query = <<<GREMLIN
g.V.has('constante', true).count();
GREMLIN;
        $vertices = $this->gremlin->query($query);
        $constantes = $vertices[0];
        display( 'Constante tokens : '.$constantes." (".number_format($constantes / $total * 100, 2)."% )\n");

    }

    private function displayTiming($message) {
        display( $message."\nTime : ".number_format($this->lastTiming * 1000, 0)."ms\n");
    }
}

?>
