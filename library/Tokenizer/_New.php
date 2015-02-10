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

class _New extends TokenAuto {
    static public $operators = array('T_NEW');
    static public $atom = 'New';

    public function _check() {
        $this->conditions = array(0 => array('token'      => _New::$operators,
                                             'atom'       => 'none'),
                                  1 => array('atom'       => array('Functioncall', 'Constant', 'Variable', 'Methodcall', 'String',
                                                                   'Array', 'Property', 'Staticproperty', 'Staticmethodcall',
                                                                   'Nsname', 'Identifier',)),
                                  2 => array('filterOut2' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_NS_SEPARATOR',
                                                                   'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_DOUBLE_COLON' )),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NEW'),
                               'atom'         => 'New',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

it.setProperty('fullcode', "new " + it.out("NEW").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
