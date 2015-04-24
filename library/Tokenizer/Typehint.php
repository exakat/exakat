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


namespace Tokenizer;

class Typehint extends TokenAuto {
    static public $operators = array('T_COMMA', 'T_OPEN_PARENTHESIS');
    static public $atom = 'Typehint';
    
    public function _check() {
        $atoms = array('Variable', 'Assignation', 'Identifier');
        
        // normal case for classes
        $this->conditions = array( 0 => array('token'     => Typehint::$operators),
                                   1 => array('atom'      => 'yes',
                                              'token'     => array('T_STRING', 'T_NS_SEPARATOR', 'T_CALLABLE', 'T_ARRAY')),
                                   2 => array('atom'      => $atoms),
                                   3 => array('filterOut' => Assignation::$operators),
        );
        
        $this->actions = array('toTypehint'  => true,
                               'keepIndexed' => true);
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("CLASS").next().getProperty("fullcode") + " " + fullcode.out("VARIABLE").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
