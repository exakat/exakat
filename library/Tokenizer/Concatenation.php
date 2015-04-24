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

class Concatenation extends TokenAuto {
    public static $operators = array('T_DOT');
    static public $atom = 'Concatenation';
    
    public function _check() {
        $operands = array('String', 'Identifier', 'Integer', 'Float', 'Not', 'Variable', 'Array', 'Concatenation', 'Sign', 'Array',
                          'Functioncall', 'Noscream', 'Staticproperty', 'Staticmethodcall', 'Staticconstant', 'Staticclass',
                          'Methodcall', 'Parenthesis', 'Magicconstant', 'Property', 'Multiplication', 'Addition', 'Power',
                          'Preplusplus', 'Postplusplus', 'Cast', 'Assignation', 'Nsname', 'Boolean', 'Null', 'Shell', 'Power',
                          'Heredoc', 'New' );
        
        $this->conditions = array(-2 => array('token' => array_merge( Assignation::$operators, Comparison::$operators,
                                                                      Logical::$operators, _Include::$operators,
                                                                      Bitshift::$operators, _Clone::$operators,
                                                                      Ternary::$operators, _Return::$operators,
                                                                      Keyvalue::$operators, Rawstring::$operators,
                                                                      array('T_COLON', 'T_COMMA', 'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS',
                                                                            'T_OPEN_CURLY', 'T_OPEN_BRACKET',
                                                                            'T_ECHO', 'T_PRINT','T_OPEN_TAG',
                                                                            'T_SEMICOLON', 'T_CASE', 'T_DOLLAR_OPEN_CURLY_BRACES',
                                                                            'T_ELSE', 'T_YIELD'))),
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => 'T_DOT',
                                              'check_for_concatenation' => $operands),
                                   1 => array('atom'  => $operands),
                                   2 => array('token' => array_merge(Comparison::$operators, Logical::$operators,
                                                          Power::$operators, Addition::$operators, Multiplication::$operators,
                                                          Token::$alternativeEnding,
                                                          array('T_CLOSE_PARENTHESIS', 'T_COLON', 'T_SEMICOLON', 'T_CLOSE_TAG',
                                                                'T_CLOSE_CURLY', 'T_CLOSE_BRACKET', 'T_DOT', 'T_QUESTION',
                                                                'T_COMMA', 'T_DOUBLE_ARROW', 'T_ELSEIF', 'T_INLINE_HTML'))),
        );
        
        $this->actions = array('to_concatenation' => true,
                               'atom'             => 'Concatenation',
                               'makeSequence'     => 'x',
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
s = [];
fullcode.out("CONCAT").sort{it.rank}._().each{ s.add(it.fullcode); };
fullcode.setProperty('fullcode', "" + s.join(" . ") + "");
fullcode.setProperty('count', s.size());

GREMLIN;
    }

}
?>
