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

class Property extends TokenAuto {
    static public $operators = array('T_OBJECT_OPERATOR');
    static public $atom = 'Property';
    
    public function _check() {
        $operands = array('Variable', 'Property', 'Array', 'Staticmethodcall', 'Staticproperty', 'Methodcall',
                          'Functioncall', 'Parenthesis');

        // $object->property{1}
        $this->conditions = array( -2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_DOLLAR')),
                                   -1 => array('atom' => $operands),
                                    0 => array('token' => Property::$operators),
                                    1 => array('atom' => array('String', 'Variable', 'Array', 'Identifier', 'Boolean', 'Null')),
                                    2 => array('token' => array('T_OPEN_CURLY', 'T_OPEN_BRACKET')),
                                    );
        
        $this->actions = array('transform'    => array( -1 => 'OBJECT',
                                                         1 => 'PROPERTY'),
                               'atom'         => 'Property',
                               'cleanIndex'   => true);
        $this->checkAuto();
        
        // $object->property
        $this->conditions = array( -2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                   -1 => array('atom' => $operands),
                                    0 => array('token' => Property::$operators),
                                    1 => array('atom' => array('String', 'Variable', 'Array', 'Identifier', 'Boolean', 'Null')),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET')),
                                    );
        
        $this->actions = array('transform'    => array( -1 => 'OBJECT',
                                                         1 => 'PROPERTY'),
                               'atom'         => 'Property',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        // $object->{property}
        $this->conditions = array( -2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                   -1 => array('atom'  => $operands),
                                    0 => array('token' => Property::$operators),
                                    1 => array('token' => 'T_OPEN_CURLY'),
                                    2 => array('atom'  => 'yes'),
                                    3 => array('token' => 'T_CLOSE_CURLY'),
                                    4 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                    );
        
        $this->actions = array('transform'    => array( -1 => 'OBJECT',
                                                         1 => 'DROP',
                                                         2 => 'PROPERTY',
                                                         3 => 'DROP'),
                               'atom'         => 'Property',
                               'property'     => array('bracket' => true),
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
// case for \$v()
fullcode.out("NAME").each{ fullcode.fullcode = it.fullcode }

if (fullcode.bracket == true) {
    fullcode.setProperty('fullcode', fullcode.out("OBJECT").next().getProperty('fullcode') + "->{" + fullcode.out("PROPERTY").next().getProperty('fullcode') + "}");
} else {
    fullcode.setProperty('fullcode', fullcode.out("OBJECT").next().getProperty('fullcode') + "->" + fullcode.out("PROPERTY").next().getProperty('fullcode'));
}

GREMLIN;
    }
}

?>
