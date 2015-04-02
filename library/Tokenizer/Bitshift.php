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

class Bitshift extends TokenAuto {
    static public $operators = array('T_SR','T_SL');
    static public $atom = 'Bitshift';
        
    public function _check() {
        // note : Multiplication:: and Bitshift:: operators are the same!
        $this->conditions = array(-2 => array('filterOut' => array_merge(Property::$operators,      Staticproperty::$operators,
                                                                         Concatenation::$operators, Bitshift::$operators)),
                                  -1 => array('atom'      => array_merge(array('Bitshift'), Multiplication::$operands)),
                                   0 => array('token'     => Bitshift::$operators,
                                             'atom'       => 'none'),
                                   1 => array('atom'      => Multiplication::$operands),
                                   2 => array('filterOut' => array_merge(Functioncall::$operators, Block::$operators,
                                                                         _Array::$operators,       Concatenation::$operators,
                                                                         Property::$operators,     Staticproperty::$operators,
                                                                         Parenthesis::$operators))
        );
        
        $this->actions = array('transform'    => array( 1 => 'RIGHT',
                                                       -1 => 'LEFT'),
                               'atom'         => 'Bitshift',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.out("LEFT").next().getProperty('fullcode') + " " + fullcode.getProperty('code')
                                    + " " + fullcode.out("RIGHT").next().getProperty('fullcode') );

GREMLIN;

    }
}
?>
