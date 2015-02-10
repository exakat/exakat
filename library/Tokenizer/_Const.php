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

class _Const extends TokenAuto {
    static public $operators = array('T_CONST');
    static public $atom = 'Const';

    public function _check() {
    // class x { const a = 2, b = 2, c = 3; }
        $this->conditions = array( -1 => array('notToken'  => 'T_USE'),
                                    0 => array('token'     => _Const::$operators),
                                    1 => array('atom'      => 'Arguments'),
                                    2 => array('filterOut' => 'T_COMMA'),
                                 );
        
        $this->actions = array('to_const'     => true);
        $this->checkAuto();

    // class x {const a = 2; } only one.
        $this->conditions = array( -1 => array('notToken' => 'T_USE'),
                                    0 => array('token'    =>  _Const::$operators),
                                    1 => array('atom'     => 'Assignation'),
                                    2 => array('token'    => 'T_SEMICOLON')
                                 );
        
        $this->actions = array('to_const_assignation' => true,
                               'atom'                 => 'Const',
                               'cleanIndex'           => true,
                               'makeSequence'         => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', "const " + fullcode.out("NAME").next().getProperty('code') + " = " + fullcode.out("VALUE").next().getProperty('fullcode'));

GREMLIN;
    }
}
?>
