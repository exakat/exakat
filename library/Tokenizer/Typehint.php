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

        $allowedTokens = array('T_TYPEHINT', 'T_VARIABLE', 'T_STRING', 'T_NS_SEPARATOR',
                               'T_CALLABLE', 'T_ARRAY', 'T_EQUAL');
        $allowedAtoms = array('Variable', 'Assignation');
        
        // normal case for classes
        $this->conditions = array( 0 => array('token'             => Typehint::$operators,
                                               'checkForTypehint' => $allowedAtoms),
                                   1 => array('token'             => $allowedTokens),
                                   2 => array('token'             => array('T_COMMA', 'T_CLOSE_PARENTHESIS'))
        );
        
        $this->actions = array('toTypehint'  => true,
                               'keepIndexed' => true
                               );
        $this->checkAuto();

        // normal case for classes
        $this->conditions = array( 0 => array('token'             => Typehint::$operators,
                                              'checkForTypehint'  => $allowedAtoms),
                                   1 => array('token'             => $allowedTokens),
                                   2 => array('token'             => array('T_EQUAL', 'T_VARIABLE'),
                                              'atom'              => 'yes')
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
