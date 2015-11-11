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

class Sign extends TokenAuto {
    static public $operators = array('T_PLUS', 'T_MINUS');
    static public $operands = array('Sign', 'String', 'Variable', 'Array', 'Float', 'Boolean', 'Functioncall', 'Null',
                                    'Staticmethodcall', 'Staticproperty', 'Multiplication', 'Property', 'Parenthesis',
                                    'Methodcall', 'Cast', 'Constant', 'Boolean', 'Identifier', 'Assignation', 'Staticconstant');
    static public $atom = 'Sign';

    public function _check() {
        if (version_compare('7.0', self::$phpExecVersion) > 0) {
            $php7Precedence = array('T_YIELD');
        } else {
            $php7Precedence = array();
        }
        
        //  + -1  (special case for Integers)
        $this->conditions = array( -1 => array('notToken' => array_merge(array('T_STRING', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                                                               'T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER',
                                                                               'T_CLOSE_PARENTHESIS', 'T_VARIABLE',
                                                                               'T_CLOSE_BRACKET', 'T_SHELL_QUOTE',
                                                                               'T_QUOTE_CLOSE', 'T_QUOTE', 'T_SHELL_QUOTE_CLOSE',
                                                                               'T_DOLLAR', 'T_CLOSE_CURLY', 'T_FUNCTION'),
                                                                          $php7Precedence,
                                                                          Magicconstant::$operators,
                                                                          Preplusplus::$operators,
                                                                          PostPlusPlus::$operators),
                                               'notAtom'    => array('Sign', 'Addition', 'Array', 'Parenthesis', 'Noscream',
                                                                     'Multiplication', 'Cast', 'Integer', 'Float', 'Function',
                                                                     'Concatenation', 'Power' )),
                                    0 => array('token'      => Sign::$operators,
                                               'atom'       => 'none'),
                                    1 => array('atom'       => 'Integer'),
                                    2 => array('filterOut'  => Multiplication::$operators),
                                 );
        
        $this->actions = array('atom'        => 'Integer',
                               'minusIntval' => true,
                               'sign'        => true,
                               'property'    => array('scalar' => true,
                                                      'instruction' => true,
                                                      'signed' => true),
                               'cleanIndex'  => true
                               );
        $this->checkAuto();
        
        //  + -$s (Normal case)
        //'T_OPEN_CURLY',
        $this->conditions = array( -1 => array('notToken' => array_merge(array('T_STRING', 'T_ARRAY', 'T_DOT',
                                                                               'T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER',
                                                                               'T_CLOSE_PARENTHESIS', 'T_VARIABLE',
                                                                               'T_CLOSE_BRACKET','T_DOLLAR', 'T_CLOSE_CURLY',
                                                                               'T_FUNCTION', 'T_INC', 'T_DEC',
                                                                               'T_QUOTE', 'T_QUOTE_CLOSE',
                                                                               'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_NS_SEPARATOR'),
                                                                          $php7Precedence,
                                                                          Magicconstant::$operators,
                                                                          Bitshift::$operators),
                                               'notAtom'    => array('Sign', 'Addition', 'Array', 'Parenthesis', 'Noscream',
                                                                     'Multiplication', 'Cast', 'Integer', 'Float', 'Function',
                                                                     'Concatenation', 'Power')),
                                    0 => array('token'      => Sign::$operators),
                                    1 => array('atom'       => Sign::$operands),
                                    2 => array('filterOut'  => array_merge( Methodcall::$operators, Parenthesis::$operators,
                                                                            _Array::$operators,     Block::$operators,
                                                                            Property::$operators,   Staticproperty::$operators)),
                                 );
        
        $this->actions = array('transform'    => array( 1 => 'SIGN'),
                               'atom'         => 'Sign',
                               'property'     => array('scalar' => true,
                                                       'instruction' => true),
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it'
                               );
        $this->checkAuto();

// This special case is needed for situation like 1 . 2 + 3 and -'a' . -'b';
        $this->conditions = array( -1 => array('token' => 'T_DOT',
                                               'atom'  => 'none'),
                                   0  => array('token' => Sign::$operators,
                                               'atom'  => 'none'),
                                   1  => array('atom'  => Sign::$operands),
                                   2  => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                                                    'T_OPEN_CURLY', 'T_OPEN_BRACKET')),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true),
                               'cleanIndex' => true);
        $this->checkAuto();

//Special cases like 1 * -2 or 2 + -2
        $this->conditions = array( -1 => array('token' => array_merge(Addition::$operators, Multiplication::$operators),
                                               'atom'  => 'none'),
                                    0 => array('token' => Sign::$operators,
                                               'atom'  => 'none'),
                                    1 => array('atom'  => Sign::$operands),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                                                    'T_OPEN_CURLY', 'T_OPEN_BRACKET')));
        
        $this->actions = array('transform'  => array( 1 => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true),
                               'cleanIndex' => true);
        $this->checkAuto();
                
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
if (fullcode.out('SIGN').count() > 0) {
    fullcode.fullcode = fullcode.code + fullcode.out("SIGN").next().fullcode;
} else {
    fullcode.fullcode = fullcode.code;
}
GREMLIN;
    }
}

?>
