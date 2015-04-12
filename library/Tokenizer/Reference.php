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

class Reference extends TokenAuto {
    static public $operators = array('T_AND');
    static public $atom = 'Reference';

    public function _check() {
        $this->conditions = array(-1 => array('filterOut2' => array_merge(Logical::$operators, Staticproperty::$operators,
                                                                array('T_VARIABLE', 'T_LNUMBER', 'T_DNUMBER', 'T_STRING',
                                                                      'T_MINUS', 'T_PLUS', 'T_CLOSE_PARENTHESIS',
                                                                      'T_CLOSE_BRACKET', 'T_CLOSE_PARENTHESIS', 'T_CONSTANT_ENCAPSED_STRING')),
                                              'notAtom'    => array('Parenthesis', 'Array', 'Comparison', 'Bitshift', 'Not', 'Functioncall',
                                                                    'Noscream', 'Methodcall')),
                                   0 => array('token'      => Reference::$operators,
                                              'atom'       => 'none'),
                                   1 => array('atom'       => array('Variable', 'Array', 'Property', 'Functioncall',
                                                                    'Methodcall', 'Staticmethodcall', 'Staticproperty',
                                                                    'New', 'Arrayappend')),
                                   2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET', 'T_OPEN_CURLY',
                                                                   'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
        );
        
        $this->actions = array('transform'    => array( 0          => 'DROP'),
                               'propertyNext' => array('reference' => true,
                                                       'fullcode'  => 'it.fullcode'),
                               'fullcode'     => true
                               );
        $this->setAtom = true;
        $this->checkAuto();

        // special case for Stdclass &$x =
        $this->conditions = array(-2 => array('filterOut' => 'T_DOUBLE_COLON'),
                                  -1 => array('token'     => 'T_STRING',
                                              'atom'      => 'none'),
                                   0 => array('token'     => Reference::$operators,
                                              'atom'      => 'none'),
                                   1 => array('atom'      => 'Variable'),
                                   2 => array('token'     => array('T_COMMA', 'T_EQUAL', 'T_CLOSE_PARENTHESIS'))
        );
        
        $this->actions = array('transform'    => array( 0 => 'DROP'),
                               'propertyNext' => array('reference' => true,
                                                       'fullcode'  => '"&" + fullcode.code'));
        $this->checkAuto();

        // special case for &function x()
        $this->conditions = array(-1 => array('token' => 'T_FUNCTION',
                                              'atom'  => 'none'),
                                  0 => array('token'  => Reference::$operators),
                                  1 => array('atom'   => 'Identifier'),
                                  2 => array('token'  => 'T_OPEN_PARENTHESIS'),
                                  3 => array('atom'   => 'Arguments'),
                                  4 => array('token'  => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('transform'    => array( 0 => 'DROP'),
                               'propertyNext' => array('reference' => true),
        );
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty("fullcode", "&" + fullcode.getProperty("fullcode"));

GREMLIN;
    }
}

?>
