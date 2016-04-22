<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Functioncall extends TokenAuto {
    static public $operators            = array('T_VARIABLE', 'T_DOLLAR', 'T_STRING', 'T_UNSET', 'T_EMPTY', 'T_ARRAY',
                                                'T_ECHO',
                                                'T_NS_SEPARATOR', 'T_ISSET', 'T_LIST', 'T_EVAL',
                                                'T_EXIT', 'T_STATIC', 'T_PRINT', 'T_OPEN_PARENTHESIS',
                                                'T_WHILE', 'T_FOREACH', 'T_DO',
                                                'T_DOUBLE_COLON');
    static public $operatorsWithoutEcho = array('T_VARIABLE', 'T_DOLLAR', 'T_STRING', 'T_UNSET', 'T_EMPTY', 'T_ARRAY',
                                                'T_NS_SEPARATOR', 'T_ISSET', 'T_LIST', 'T_EVAL',
                                                'T_EXIT', 'T_STATIC', 'T_OPEN_PARENTHESIS',
                                                'T_WHILE', 'T_FOREACH', 'T_DO');
    static public $atom = 'Functioncall';

    public function _check() {
        // functioncall(with arguments or void) that will be in a sequence
        // No -> or ::, but OK as atoms.
        $this->conditions = array(  -1 => array('filterOut' => array('T_FUNCTION', 'T_NS_SEPARATOR', 'T_EVAL')),
                                     0 => array('token'     => array_merge(static::$operatorsWithoutEcho, array('T_OPEN_PARENTHESIS'))),
                                     1 => array('atom'      => 'none',
                                                'token'     => 'T_OPEN_PARENTHESIS'),
                                     2 => array('atom'      =>  array('Arguments', 'Void')),
                                     3 => array('atom'      => 'none',
                                                'token'     => 'T_CLOSE_PARENTHESIS'),
                                     4 => array('notToken'  => 'T_OPEN_PARENTHESIS')
        );
        
        $this->actions = array('transform' => array( 1 => 'DROP',
                                                     2 => 'ARGUMENTS',
                                                     3 => 'DROP'
                                                     ),
                               'atom'      => 'Functioncall',
                               'property'  => array('parenthesis' => true)
                               );
        $this->checkAuto();

        return true;
        // functioncall(with arguments or void) with another function as name (initial name is $variable or string)
        $this->conditions = array(   0 => array('token' => array('T_STRING', 'T_VARIABLE', 'T_NS_SEPARATOR', 'T_OBJECT_OPERATOR',
                                                                 'T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS'),
                                                'atom'  => 'yes'),
                                     1 => array('atom'  => 'none',
                                                'token' => 'T_OPEN_PARENTHESIS' ),
                                     2 => array('atom'  =>  array('Arguments', 'Void')),
                                     3 => array('atom'  => 'none',
                                                'token' => 'T_CLOSE_PARENTHESIS'),
                                     4 => array('token' => 'T_OPEN_PARENTHESIS')
        );
        
        $this->actions = array('functionToFunctioncall' => 1,
                               'keepIndexed'            => true,
                               'property'               => array('parenthesis' => true),
                               );
        $this->checkAuto();
        
        // $functioncall(with arguments or void) with a variable as name
        $this->conditions = array(   0 => array('token'    => 'T_VARIABLE'),
                                     1 => array('atom'     => 'none',
                                                'token'    => 'T_OPEN_PARENTHESIS' ),
                                     2 => array('atom'     =>  array('Arguments', 'Void')),
                                     3 => array('atom'     => 'none',
                                                'token'    => 'T_CLOSE_PARENTHESIS'),
                                     4 => array('notToken' => 'T_OPEN_PARENTHESIS')
        );
        
        $this->actions = array('variableToFunctioncall' => 1,
                               'keepIndexed'            => true,
                               'property'               => array('parenthesis' => true),
                               );
        $this->checkAuto();

        // functioncall(with arguments but without parenthesis)
        $this->conditions = array(-1 => array('filterOut' => array_merge(_Ppp::$operators, _Function::$operators)),
                                   0 => array('token'     => array('T_ECHO', 'T_PRINT', 'T_EXIT'),
                                              'atom'      => 'none'),
                                   1 => array('atom'      => 'Arguments'),
                                   2 => array('notToken'  => array_merge( array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON'),
                                                                          Addition::$operators, Multiplication::$operators,
                                                                          Bitshift::$operators, Logical::$booleans,
                                                                          Ternary::$operators)),
        );
        
        $this->actions = array('transform'    => array(1 => 'ARGUMENTS'),
                               'atom'         => 'Functioncall',
                               'addSemicolon' => 'it',
                               'property'     => array('parenthesis' => false),
                               );
        $this->checkAuto();

        // special case for new static with arguments or void
        $this->conditions = array(-1 => array('token' => 'T_NEW'),
                                   0 => array('token' => 'T_STATIC',
                                              'atom'  => 'none'),
                                   1 => array('atom'  => array('Arguments', 'Void')), // actually, T_VOID
        );
        
        $this->actions = array('transform'    => array(1 => 'ARGUMENTS'),
                               'atom'         => 'Functioncall',
                               'addSemicolon' => 'it'
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (o.property('token') == 'T_NS_SEPARATOR') {
    s = g.V(o).out("SUBNAME").order().by('rank').values('fullcode').join('\\\\');
    
    if (o.property('absolutens') == true) {
//        fullcode = "\\\\" + s;
    } else {
//        fullcode = s;
    }
} else if (o.property('token').value() == 'T_OBJECT_OPERATOR') {
    // Do nothing.
} else if (o.property('token').value() == 'T_DOUBLE_COLON') {
    // Do nothing.
} else if (o.property('token').value() == 'T_OPEN_PARENTHESIS') {
    // Do nothing.
} else if (g.V(o).properties('fullcode').count().is(neq(0))) {
    fullcode = o.property('fullcode').value();
} else {
    fullcode = o.property('code').value();
}

if (o.property('parenthesis').value() == true) {
    fullcode = fullcode + "(" + g.V(o).out("ARGUMENTS").next().property('fullcode').value() + ")";
} else {
    fullcode = fullcode + " " + g.V(o).out("ARGUMENTS").next().property('fullcode').value() + "";
}

args_count = g.V(o).out('ARGUMENTS').out('ARGUMENT').not(has('token', 'T_VOID')).size();
o.property("args_count", args_count);

GREMLIN;
    }

}
?>
