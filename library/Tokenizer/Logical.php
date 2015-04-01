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

class Logical extends TokenAuto {
    static public $operators = array('T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
                                     'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
                                     'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR');
    // and or xor
    static public $logicals = array('T_LOGICAL_AND', 'T_ANDAND',
                                    'T_LOGICAL_OR' , 'T_OROR',
                                    'T_LOGICAL_XOR', );
    // || && ^
    static public $booleans = array('T_AND', 'T_BOOLEAN_AND',
                                    'T_OR' , 'T_BOOLEAN_OR',
                                    'T_XOR', 'T_BOOLEAN_XOR');
    static public $atom = 'Logical';

    public function _check() {
        $filterOut = array_merge(Comparison::$operators,     Bitshift::$operators,
                                 Addition::$operators,       Multiplication::$operators,
                                 Concatenation::$operators,  _Instanceof::$operators,
                                 Preplusplus::$operators,    //Assignation::$operators,
                                 _New::$operators,           Property::$operators,
                                 Staticproperty::$operators, Nsname::$operators,
                                 Noscream::$operators,       Not::$operators,
                                 Reference::$operators);
        // logical boolean (and, or)
        $this->conditions = array( -2 => array('filterOut' => $filterOut),
                                   -1 => array('atom'      => 'yes',
                                               'notAtom'   => 'Sequence'),
                                    0 => array('token'     => Logical::$logicals,
                                               'atom'      => 'none'),
                                    1 => array('atom'      => 'yes',
                                               'notAtom'   => 'Sequence'),
                                    2 => array('filterOut' => array_merge(Comparison::$operators, Assignation::$operators,
                                                                          Addition::$operators, Multiplication::$operators,
                                                                          Bitshift::$operators, Concatenation::$operators,
                                                                           array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET',
                                                                                 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_INC', 'T_DEC',
                                                                                 'T_NS_SEPARATOR',
                                                                           ))));
        
        $this->actions = array('transform'    => array( -1 => 'LEFT',
                                                         1 => 'RIGHT'),
                               'atom'         => 'Logical',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // boolean comparison (||, &&)
        $this->conditions = array( -2 => array('filterOut' => $filterOut),
                                   -1 => array('atom'      => 'yes',
                                               'notAtom'   => 'Sequence'),
                                    0 => array('token'     => Logical::$booleans,
                                               'atom'      => 'none'),
                                    1 => array('atom'      => 'yes',
                                               'notAtom'   => array('Sequence', 'Assignation')),
                                    2 => array('filterOut' => array_merge(Comparison::$operators, Assignation::$operators,
                                                                          Addition::$operators, Multiplication::$operators,
                                                                          Bitshift::$operators, Concatenation::$operators,
                                                                          Logical::$logicals,
                                                                           array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET',
                                                                                 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_INC', 'T_DEC',
                                                                                 'T_NS_SEPARATOR',
                                                                           ))));
        
        $this->actions = array('transform'    => array( -1 => 'LEFT',
                                                         1 => 'RIGHT'),
                               'atom'         => 'Logical',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
        
fullcode.fullcode = fullcode.out("LEFT").next().fullcode + " " + fullcode.code + " " + it.out("RIGHT").next().fullcode;

GREMLIN;
    }
}
?>
