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
                          'As', 'Power', 'Staticclass', 'Yield', 'Shell', 'Heredoc'
                           );
        $operands = 'yes';
        
        $yieldOperator = array('T_ECHO', 'T_PRINT', 'T_DOT', 'T_AT', 'T_OBJECT_OPERATOR', 'T_BANG',
                               'T_DOUBLE_COLON', 'T_COLON', 'T_NEW', 'T_INSTANCEOF', 'T_RETURN', 'T_DOUBLE_ARROW',
                               'T_AND', 'T_QUOTE', 'T_DOLLAR', 'T_VAR', 'T_CONST', 'T_COMMA',
                               'T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC', 'T_INC', 'T_DEC', 'T_GLOBAL', 'T_NS_SEPARATOR',
                               'T_GOTO', 'T_STATIC', 'T_OPEN_PARENTHESIS', 'T_ELSE', 'T_ELSEIF', 'T_CLOSE_PARENTHESIS',
                               'T_THROW', 'T_CATCH', 'T_ABSTRACT', 'T_CASE', 'T_DEFAULT', 'T_CLONE', 'T_DECLARE',
                               'T_STRING', 'T_USE', 'T_AS', 'T_NAMESPACE', 'T_DO', 'T_INSTEADOF', 'T_CONTINUE'
                                );
                                 
        $yieldOperator = array_merge($yieldOperator, Assignation::$operators, Addition::$operators, Multiplication::$operators,
                                      Comparison::$operators, Cast::$operators, Logical::$operators, Bitshift::$operators,
                                      _Include::$operators, Power::$operators );
                                      
        $forbiddenTokens = array('T_ELSEIF', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT', 'T_COMMA');

        // Actual rules starting now

        // @note : $x; endif
        $this->conditions = array(-2 => array('token'    => 'T_COLON',
                                              'property' => array('association' => array('Ifthen', 'Switch', 'While', 'Case', 'Default', 'Declare', 'For', 'Foreach'))),
                                  -1 => array('atom'     => $operands,
                                              'notToken' => $forbiddenTokens ),
                                   0 => array('token'    => Sequence::$operators,
                                              'atom'     => 'none'),
                                   1 => array('token'    => array('T_ENDIF', 'T_ELSEIF', 'T_ELSE', 'T_ENDSWITCH', 'T_ENDWHILE', 'T_CASE', 'T_ENDDECLARE', 'T_ENDFOR', 'T_ENDFOREACH')),
        );
        
        $this->actions = array('toSequence'  => true,
                               'keepIndexed' => true);
        $this->checkAuto();

        // @note instructions separated by ;
        $this->conditions = array(-2 => array('filterOut2' => $yieldOperator,
                                              'filterOut'  => 'T_IF',
                                              'notAtom'    => 'Parenthesis'),
                                  -1 => array('atom'       => $operands,
                                              'notToken'   => $forbiddenTokens),
                                   0 => array('token'      => Sequence::$operators),
                                   1 => array('atom'       => $operands,
                                              'notToken'   => $forbiddenTokens)
        );
        
        $this->actions = array('toSequence'  => true,
                               'keepIndexed' => true);
        $this->checkAuto();

        // reenter a sequence in building
        $this->conditions = array( 0 => array('token'    => Sequence::$operators,
                                              'atom'     => 'yes'),
                                   1 => array('atom'     => $operands,
                                              'notToken' => $forbiddenTokens)
        );
        
        $this->actions = array('toSequence'  => true,
                               'keepIndexed' => true);
        $this->checkAuto();

        // reenter a sequence in building (special case with : for alternative syntax)
        $this->conditions = array( -2 => array('token'    => 'T_COLON',
                                               'property' => array('association' => array('Ifthen', 'Switch', 'While', 'Case', 'Default', 'Declare', 'For', 'Foreach'))),
                                   -1 => array('atom'     => $operands,
                                               'notToken' => $forbiddenTokens),
                                    0 => array('token'    => Sequence::$operators,
                                               'atom'     => 'none'),
                                    1 => array('atom'     => $operands,
                                               'notToken' => $forbiddenTokens)
        );
        
        $this->actions = array('toSequence'  => true,
                               'keepIndexed' => true);
        $this->checkAuto();

        // { 2; }
        $this->conditions = array( -2 => array('token'    => 'T_OPEN_CURLY'),
                                   -1 => array('atom'     => $operands,
                                               'notToken' => $forbiddenTokens ),
                                    0 => array('token'    => Sequence::$operators,
                                               'atom'     => 'none'),
                                    1 => array('token'    => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('toOneSequence'  => true);
        $this->checkAuto();

        // <?php 2 ; ? >
        $this->conditions = array( -2 => array('token'    => 'T_OPEN_TAG'),
                                   -1 => array('atom'     => $operands,
                                               'notToken' => $forbiddenTokens ),
                                    0 => array('token'    => Sequence::$operators),
                                    1 => array('token'    => 'T_CLOSE_TAG'),
        );
        
        $this->actions = array('toOneSequence'  => true);
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
