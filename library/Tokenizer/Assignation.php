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

class Assignation extends TokenAuto {
    static public $operators =array( 'T_AND_EQUAL',
                                     'T_CONCAT_EQUAL',
                                     'T_EQUAL',
                                     'T_DIV_EQUAL',
                                     'T_MINUS_EQUAL',
                                     'T_MOD_EQUAL',
                                     'T_MUL_EQUAL',
                                     'T_OR_EQUAL',
                                     'T_PLUS_EQUAL',
                                     'T_SL_EQUAL',
                                     'T_SR_EQUAL',
                                     'T_XOR_EQUAL',
                                     'T_SL_EQUAL',
                                     'T_SR_EQUAL',
                                     'T_POW_EQUAL'
                                     );
    static public $atom = 'Assignation';
    
    public function _check() {
        $operands = array('Integer', 'Multiplication', 'Addition', 'Not',
                          'Array', 'Float', 'Concatenation', 'Property',
                          'Parenthesis', 'Noscream', 'Ternary', 'New', 'String',
                          'Constant', 'Functioncall', 'Staticproperty', 'Staticconstant', 'Staticclass', 'Property',
                          'Heredoc', 'Preplusplus', 'Postplusplus', 'Methodcall', 'Nsname',
                          'Assignation', 'Variable', 'Boolean', 'Null', 'Magicconstant',
                          'Cast', 'Staticmethodcall', 'Sign', 'Logical', 'Bitshift', 'Comparison',
                          'Clone', 'Shell', 'Include', 'Instanceof', 'Function', 'ArrayNS', 'Identifier',
                          'Arrayappend', 'Power', 'Spaceship', 'Coalesce'
                         );
        $filterOut2 = array_merge(Assignation::$operators, Addition::$operators, Bitshift::$operators,
                                  Comparison::$operators, Logical::$booleans, Multiplication::$operators,
                                  Postplusplus::$operators, Power::$operators, _Instanceof::$operators,
                                  Coalesce::$operators, 
                                  array('T_DOT', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                        'T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET',
                                        'T_QUESTION', 'T_NS_SEPARATOR' ));

        // check for preplusplus in the yield filterout.
        // 'T_AND' used to be here, but should be processed by reference first
        $this->conditions = array(-2 => array('filterOut2' => array_merge(array('T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_DOLLAR',
                                                                                'T_AT', 'T_AND'),
                                                                           Preplusplus::$operators )),
                                  -1 => array('atom'       => array('Variable', 'Array', 'Property', 'Staticproperty', 'Functioncall',
                                                                    'Noscream', 'Not', 'Arrayappend' , 'Typehint', 'Identifier',
                                                                    'Static', 'Cast', 'Sign', 'Power', 'Null', 'Boolean' )),
                                   0 => array('token'      => Assignation::$operators),
                                   1 => array('atom'       => $operands),
                                   2 => array('filterOut2' => $filterOut2),
        );
        
        $this->actions = array('transform'    => array( 1 => 'RIGHT',
                                                       -1 => 'LEFT'),
                               'atom'         => 'Assignation',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        //$a & $b	= B($c);
        $this->conditions = array(-2 => array('token' => 'T_AND'),
                                  -1 => array('atom'  => 'Variable'),
                                   0 => array('token' => Assignation::$operators),
                                   1 => array('atom'  => $operands),
                                   2 => array('filterOut2' => $filterOut2),
        );
        
        $this->actions = array('transform'    => array( 1 => 'RIGHT',
                                                       -1 => 'LEFT'),
                               'atom'         => 'Assignation',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("LEFT").next().getProperty('fullcode') + " " + fullcode.getProperty('code') + " " + fullcode.out("RIGHT").next().getProperty('fullcode'));

GREMLIN;
    }
}
?>
