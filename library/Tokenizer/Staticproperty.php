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

class Staticproperty extends TokenAuto {
    static public $operators = array('T_DOUBLE_COLON');
    static public $atom = 'Staticproperty';

    public function _check() {
        $operands = array('Constant', 'Identifier', 'Variable', 'Array', 'Static', 'Nsname', );
        
        $this->conditions = array( -2 => array('filterOut2' => array('T_NS_SEPARATOR')),
                                   -1 => array('atom' => $operands),
                                    0 => array('token' => Staticproperty::$operators),
                                    1 => array('atom' => array('Variable', 'Array', 'Property', )),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET')));
        
        $this->actions = array('transform'    => array( -1 => 'CLASS',
                                                         1 => 'PROPERTY'),
                               'atom'         => 'Staticproperty',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("CLASS").next().getProperty('fullcode') + "::" + fullcode.out("PROPERTY").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
