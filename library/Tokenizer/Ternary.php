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

class Ternary extends TokenAuto {
    static public $operators = array('T_QUESTION');
    static public $atom = 'Ternary';
    
    public function _check() {
        
        // $a ? $b : $c
        $this->conditions = array( -2 => array('filterOut' => array_merge(  Comparison::$operators,  Logical::$operators,
                                                                            Bitshift::$operators,    Multiplication::$operators,
                                                                            Addition::$operators,    Concatenation::$operators,
                                                                            Not::$operators,         Noscream::$operators,
                                                                            _Instanceof::$operators, Property::$operators,
                                                                            Staticmethodcall::$operators)),
                                   -1 => array('atom'       => 'yes',
                                               'notAtom'    => 'Sequence'),
                                    0 => array('token'      => Ternary::$operators),
                                    1 => array('atom'       => 'yes',
                                               'notAtom'    => 'Sequence'),
                                    2 => array('token'      => 'T_COLON',
                                               'property'   => array('association' => 'Ternary')),
                                    3 => array('atom'       => 'yes',
                                               'notAtom'    => 'Sequence'),
                                    4 => array('filterOut2' => array_merge(Token::$instructionEnding, array('T_OPEN_CURLY'))),
                                 );
        
        $this->actions = array('transform'    => array( -1 => 'CONDITION',
                                                         1 => 'THEN',
                                                         2 => 'DROP',
                                                         3 => 'ELSE',
                                                        ),
                               'atom'         => 'Ternary',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        // $a ?: $b : we keep the : as 'Then', and it will have to be interpreted as $a later. May need to build a specific processing here.
        $this->conditions = array( -2 => array('filterOut' => array_merge(Comparison::$operators,  Logical::$operators,
                                                                          Bitshift::$operators,    Multiplication::$operators,
                                                                          Addition::$operators,    Concatenation::$operators,
                                                                          Not::$operators,         Noscream::$operators,
                                                                          _Instanceof::$operators, Property::$operators,
                                                                          Staticmethodcall::$operators)),
                                   -1 => array('atom'       => 'yes',
                                               'notAtom'    => 'Sequence'),
                                    0 => array('token'      => Ternary::$operators),
                                    1 => array('token'      => 'T_COLON'),
                                    2 => array('atom'       => 'yes',
                                               'notAtom'    => 'Sequence'),
                                    3 => array('filterOut'  => array_merge(Token::$instructionEnding, array('T_OPEN_CURLY')))
                                 );
        
        $this->actions = array('transform'    => array( -1 => 'CONDITION',
                                                         1 => 'THEN',
                                                         2 => 'ELSE'
                                                       ),
                               'atom'         => 'Ternary',
                               'atom1'        => 'TernaryElse',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (it.out("THEN").next().atom == 'TernaryElse') {
    it.fullcode = it.out("CONDITION").next().fullcode + " ?: " + it.out("ELSE").next().fullcode;
} else {
    it.fullcode = it.out("CONDITION").next().fullcode + " ? " + it.out("THEN").next().fullcode + " : " + it.out("ELSE").next().fullcode;
}

GREMLIN;
    }
}

?>
