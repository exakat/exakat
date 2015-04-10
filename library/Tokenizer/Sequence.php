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

class Sequence extends TokenAuto {
    static public $operators = array('T_SEMICOLON');
    static public $atom = 'Sequence';
    
    public function _check() {
        $operands = array('Addition', 'Multiplication', 'String', 'Integer', 'Sequence',
                          'Float', 'Not', 'Variable','Array','Concatenation', 'Sign',
                          'Functioncall', 'Constant', 'Parenthesis', 'Comparison', 'Assignation',
                          'Noscream', 'Staticproperty', 'Property', 'Ternary', 'New', 'Return',
                          'Instanceof', 'Magicconstant', 'Staticconstant', 'Methodcall', 'Logical',
                          'Var', 'Const', 'Ppp', 'Postplusplus', 'Preplusplus', 'Global', 'Nsname',
                          'Ifthen', 'Include', 'Function', 'Foreach', 'While', 'Arrayappend', 'Cast',
                          'Break', 'Goto', 'Label', 'Switch', 'Staticmethodcall',
                          'Static', 'Continue', 'Class', 'For', 'Throw', 'Try', 'Abstract', 'Final',
                          'Bitshift', 'Void', 'Dowhile', 'Clone', 'Declare', 'Halt', 'Interface',
                          'RawString', 'Namespace', 'Boolean', 'Null', 'Use', 'ArrayNS', 'Identifier', 'Trait',
                          'As', 'Power', 'Staticclass', 'Yield', 'Shell'
                           );
        
        $yieldOperator = array('T_ECHO', 'T_PRINT', 'T_DOT', 'T_AT', 'T_OBJECT_OPERATOR', 'T_BANG',
                               'T_DOUBLE_COLON', 'T_COLON', 'T_NEW', 'T_INSTANCEOF', 'T_RETURN',
                               'T_AND', 'T_QUOTE', 'T_DOLLAR', 'T_VAR', 'T_CONST', 'T_COMMA',
                               'T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC', 'T_INC', 'T_DEC', 'T_GLOBAL', 'T_NS_SEPARATOR',
                               'T_GOTO', 'T_STATIC', 'T_OPEN_PARENTHESIS', 'T_ELSE', 'T_ELSEIF', 'T_CLOSE_PARENTHESIS',
                               'T_THROW', 'T_CATCH', 'T_ABSTRACT', 'T_CASE', 'T_DEFAULT', 'T_CLONE', 'T_DECLARE',
                               'T_STRING', 'T_USE', 'T_AS', 'T_NAMESPACE', 'T_DO', 'T_INSTEADOF', 'T_CONTINUE'
                                );
                                 
        $yieldOperator = array_merge($yieldOperator, Assignation::$operators, Addition::$operators, Multiplication::$operators,
                                      Comparison::$operators, Cast::$operators, Logical::$operators, Bitshift::$operators,
                                      _Include::$operators, Power::$operators );
        $nextOperator = array_merge(array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_COMMA', 'T_INSTANCEOF',
                                           'T_CLOSE_PARENTHESIS', 'T_CATCH', 'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_NS_SEPARATOR', 'T_AS', 'T_COLON' ),
                                     Assignation::$operators, Logical::$operators, Comparison::$operators,
                                     Preplusplus::$operators, Postplusplus::$operators, Ternary::$operators,
                                     Addition::$operators, Multiplication::$operators
                                     );

        // @note instructions separated by ;
        $this->conditions = array(-2 => array('filterOut2' => $yieldOperator,
                                              'filterOut'  => array('T_IF'),
                                              'notAtom'    => 'Parenthesis'),
                                  -1 => array('atom'       => $operands,
                                              'notToken'   => 'T_ELSEIF' ),
                                   0 => array('token'      => Sequence::$operators,
                                              'atom'       => 'none'),
                                   1 => array('atom'       => $operands,
                                              'notToken'   => 'T_ELSEIF'),
                                   2 => array('filterOut2' => $nextOperator),
        );
        
        $this->actions = array('transform'   => array( 1 => 'ELEMENT',
                                                      -1 => 'ELEMENT'),
                               'rank'        => array( 1 => 1,
                                                      -1 => 0 ),
                               'mergeNext'   => array('Sequence' => 'ELEMENT'),
                               'atom'        => 'Sequence',
                               'cleanIndex'  => true,
                               'keepIndexed' => true,
                               );
        $this->checkAuto();

        // @note instructions separated by ; but ; is useless
        $this->conditions = array(-1 => array('atom'     => $operands,
                                              'notToken' => 'T_ELSEIF' ),
                                   0 => array('token'    => Sequence::$operators,
                                              'atom'     => 'none'),
                                   1 => array('token'    => array('T_ENDIF', 'T_ENDWHILE', 'T_ENDDECLARE', 'T_ENDFOREACH')),
        );
        
        $this->actions = array('transform'   => array( 0 => 'DROP'));
        $this->checkAuto();
        
        // @note instructions separated by ; but ; is useless (special case for if/elseif
        $this->conditions = array(-1 => array('atom'     => $operands,
                                              'notToken' => 'T_ELSEIF' ),
                                   0 => array('token'    => Sequence::$operators,
                                              'atom'     => 'none'),
                                   1 => array('token'    => array('T_ELSEIF', 'T_ELSE'),
                                              'atom'     => 'yes'),
        );
        
        $this->actions = array('transform'   => array( 0 => 'DROP'));
        $this->checkAuto();

        // @note instructions separated by ; with a special case for 'foreach' and 'for'.
        // @note this is not sufficient, but it seems to works pretty well and be enough.
        $this->conditions = array(-2 => array('token' => 'T_COLON',
                                              'atom'  => 'none',
                                              'property'  => array('association' => array('For', 'Foreach', 'While', 'Default',
                                                                                          'Case', 'Switch', 'If', 'Elseif', 'Else')) // Ternary, Label
                                               ),
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => Sequence::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands),
                                   2 => array('filterOut2' => $nextOperator),
        );
        
        $this->actions = array('transform'    => array( 1 => 'ELEMENT',
                                                       -1 => 'ELEMENT'),
                               'rank'         => array( 1 => 1,
                                                       -1 => 0 ),
                               'mergeNext'    => array('Sequence' => 'ELEMENT'),
                               'atom'         => 'Sequence',
                               'cleanIndex'   => true,
                               'keepIndexed'  => true
                               );
        $this->checkAuto();

    // special case for { 1; }
        $this->conditions = array(-2 => array('token' => 'T_OPEN_CURLY'),
                                  -1 => array('atom'  => 'yes',
                                              'notAtom' => 'Sequence'),
                                   0 => array('token' => Sequence::$operators),
                                   1 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'    => array(-1 => 'ELEMENT'),
                               'rank'         => array(-1 => 0 ),
                               'atom'         => 'Sequence',
                               'cleanIndex'   => true,
                               );
        $this->checkAuto();
                
        // @note ; without no more NEXT
        $this->conditions = array( 0 => array('atom'  => 'Sequence' ));
        
        $this->actions = array('checkForNext' => true,
                               'keepIndexed'  => true);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        // fullcode is not meant to reproduce the whole code, but give a quick peek at some smaller code. Just ignoring for the moment.
        return <<<GREMLIN

fullcode.setProperty("fullcode", " ");
fullcode.setProperty("count", fullcode.out('ELEMENT').count());

GREMLIN;
    }
}
?>
