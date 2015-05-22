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

class Multiplication extends TokenAuto {
    static public $operators = array('T_STAR', 'T_SLASH', 'T_PERCENTAGE');
    static public $operands = array('Integer', 'Addition', 'Variable', 'Multiplication', 'Sign', 'Not',
                                    'Parenthesis', 'Property', 'Array', 'Concatenation', 'Float',
                                    'String', 'Identifier', 'Preplusplus', 'Postplusplus', 'Nsname', 'Functioncall',
                                    'Methodcall', 'Staticmethodcall', 'Concatenation', 'Cast',
                                    'Noscream', 'Staticconstant', 'Staticproperty', 'Constant',
                                    'Boolean', 'Magicconstant', 'Assignation', 'Include', 'Power',
                                    'Staticclass', 'Null', 'Shell', 'Function' );
    static public $atom = 'Multiplication';
    
    public function _check() {

        $this->conditions = array(-2 => array('filterOut' => array_merge(Property::$operators,
                                                                         Staticproperty::$operators,
                                                                         Concatenation::$operators,
                                                                         Preplusplus::$operators,
                                                                         Power::$operators)),
                                  -1 => array('atom' => Multiplication::$operands ),
                                   0 => array('token' => Multiplication::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => Multiplication::$operands),
                                   2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET',
                                                                               'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR'),
                                                                          Assignation::$operators,
                                                                          Power::$operators)),
        );
        
        $this->actions = array('transform'    => array(  1 => 'RIGHT',
                                                        -1 => 'LEFT'),
                               'atom'         => 'Multiplication',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.out("LEFT").next().getProperty('fullcode') + " " + fullcode.getProperty('code')
                                    + " " + fullcode.out("RIGHT").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
