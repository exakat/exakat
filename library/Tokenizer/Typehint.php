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


namespace Tokenizer;

class Typehint extends TokenAuto {
    static public $operators = array('T_OPEN_PARENTHESIS');
    static public $atom = 'Typehint';
    
    public function _check() {
        // normal case for classes ($x,  or ($x) 
        $this->conditions = array( 0 => array('token'             => self::$operators,
                                               'checkForTypehint' => array('Variable', 'Assignation'),
                                               'property'         => array('association' => 'Function')
                                               ),
        );
        
        $this->actions = array('toTypehint'  => true,
                               'keepIndexed' => true
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

// This may happens during the processing
if (fullcode.atom == 'Typehint') {
    fullcode.setProperty('fullcode', fullcode.out("CLASS").next().getProperty("fullcode") + " " + fullcode.out("VARIABLE").next().getProperty('fullcode'));
}

GREMLIN;
    }
}

?>
