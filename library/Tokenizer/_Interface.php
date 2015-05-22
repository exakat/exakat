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

class _Interface extends TokenAuto {
    static public $operators = array('T_INTERFACE');
    static public $atom = 'Interface';

    public function _check() {
        // interface x {}   
        $this->conditions = array(0 => array('token'    => _Interface::$operators,
                                             'atom'     => 'none'),
                                  1 => array('atom'     => array('Identifier', 'Boolean', 'Null')),
                                  2 => array('token'    => 'T_OPEN_CURLY',
                                             'property' => array('association' => 'Interface')),
                                  3 => array('atom'     => array('Sequence', 'Void')),
                                  4 => array('token'    => 'T_CLOSE_CURLY')
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAME',
                                                        2 => 'DROP',
                                                        3 => 'BLOCK',
                                                        4 => 'DROP'),
                               'atom'         => 'Interface',
                               'makeBlock'    => 'BLOCK',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        // interface x extends y {}
        $this->conditions = array(0 => array('token' => _Interface::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Boolean', 'Null')),
                                  2 => array('token' => 'T_EXTENDS'),
                                  3 => array('atom'  => array('Arguments', 'Identifier', 'Nsname')),
                                  4 => array('token' => 'T_OPEN_CURLY'),
                                  5 => array('atom'  => array('Sequence', 'Void')),
                                  6 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'         => array( 1 => 'NAME',
                                                             2 => 'DROP',
                                                             3 => 'EXTENDS',
                                                             4 => 'DROP',
                                                             5 => 'BLOCK',
                                                             6 => 'DROP'),
                               'atom'              => 'Interface',
                               'makeBlock'         => 'BLOCK',
                               'arguments2extends' => true,
                               'cleanIndex'        => true,
                               'addSemicolon'      => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "interface " + fullcode.out("NAME").next().code;

// extends
fullcode.out("EXTENDS").each{ fullcode.fullcode = fullcode.fullcode + " extends " + it.fullcode;}

GREMLIN;
    }

}

?>
