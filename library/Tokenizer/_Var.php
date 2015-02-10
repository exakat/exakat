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

class _Var extends TokenAuto {
    static public $operators = array('T_VAR');
    static public $atom = 'Var';

    public function _check() {
    // class x { var $x }
        $this->conditions = array( 0 => array('token' => _Var::$operators),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant', 'Static' )),
                                   2 => array('filterOut' => array('T_EQUAL', 'T_COMMA'))
                                 );
        
        $this->actions = array('to_ppp'       => 1,
                               'atom'         => 'Var',
                               'makeSequence' => 'x',
                               'cleanIndex'   => true
                               );
        $this->checkAuto();

    // class x { var $x = 2 }
        $this->conditions = array( 0 => array('token' => _Var::$operators),
                                   1 => array('atom' => 'Assignation'),
                                   2 => array('token' => array('T_SEMICOLON')),
                                 );
        
        $this->actions = array('to_ppp_assignation' => true,
                               'atom'               => 'Var',
                               'makeSequence'       => 'x'
                               );

        $this->checkAuto();

    // class x { var $x, $y }
        $this->conditions = array( 0 => array('token' => _Var::$operators),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_COMMA')),
                                 );
        
        $this->actions = array('to_var_new'   => 'Var',
                               'atom'         => 'Var');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        $token = new _Function(Token::$client);
        return $token->fullcode();
    }
}
?>
