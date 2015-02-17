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

class Arguments extends TokenAuto {
    static public $operators = array('T_COMMA');
    static public $atom = 'Arguments';

    public function _check() {
        $arguments = array('String', 'Integer', 'Boolean', 'Null', 'Addition',
                           'Multiplication', 'Property', 'Methodcall',
                           'Staticmethodcall', 'Staticconstant', 'Staticproperty',
                           'New', 'Functioncall', 'Nsname', 'Identifier', 'Void',
                           'Variable', 'Array', 'Assignation', 'Typehint', 'Keyvalue',
                           'Float', 'Concatenation', 'Parenthesis', 'Cast', 'Sign',
                           'Ternary', 'Function', 'Noscream', 'As', 'Magicconstant',
                           'Logical', 'Preplusplus', 'Postplusplus', 'Not', 'Comparison',
                           'Bitshift', 'Heredoc', 'Power', 'Shell', 'Arrayappend', 'Clone',
                           'Include');
        // @note arguments separated by ,
        $this->conditions = array(-2 => array('token'   => array('T_OPEN_PARENTHESIS', 'T_ECHO', 'T_VAR', 'T_STATIC', 'T_GLOBAL', 
                                                                 'T_PUBLIC', 'T_PRIVATE', 'T_PROTECTED', 'T_FINAL', 'T_ABSTRACT',
                                                                 'T_SEMICOLON', 'T_CONST', 'T_OPEN_CURLY', 'T_OPEN_BRACKET',
                                                                 'T_FUNCTION')),
                                  -1 => array('atom'    => 'yes'),
                                   0 => array('token'   => Arguments::$operators,
                                              'atom'    => 'none'),
                                   1 => array('atom'    => 'yes',
                                              'check_for_arguments' => $arguments),
                                   2 => array('token'   => array_merge(array('T_CLOSE_PARENTHESIS', 'T_CLOSE_TAG', 'T_CLOSE_BRACKET'), 
                                                                       Logical::$operators, Comparison::$operators,
                                                                       Token::$alternativeEnding, RawString::$operators,
                                                                       Sequence::$operators, Arguments::$operators))
                                 );
        
        $this->actions = array('to_argument' => true,
                               'atom'        => 'Arguments');
        $this->checkAuto();

        // @note arguments separated by ,
        $this->conditions = array(-2 => array('token'   => array('T_IMPLEMENTS', 'T_EXTENDS', 'T_USE')),
                                  -1 => array('atom'    => 'yes'),
                                   0 => array('token'   => Arguments::$operators,
                                              'atom'    => 'none'),
                                   1 => array('atom'    => 'yes',
                                              'check_for_namelist' => array('Identifier', 'Nsname', 'As')),
                                   2 => array('token'   => array('T_OPEN_CURLY', 'T_COMMA', 'T_SEMICOLON'))
                                 );
        
        $this->actions = array('to_argument' => true,
                               'atom'        => 'Arguments');
        $this->checkAuto();
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out("ARGUMENT").sort{it.rank}._().each{ s.add(it.fullcode); };

if (s.size() == 0) {
    s = '';
} else {
    fullcode.setProperty('fullcode', s.join(", "));
}

// note : parenthesis are set in arguments (above), if needed.

GREMLIN;
    }
}
?>
