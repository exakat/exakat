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

class _Include extends TokenAuto {
    static public $operators = array('T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 'T_REQUIRE');
    static public $atom = 'Include';

    public function _check() {
        // include 'inclusion.php';
        $this->conditions = array( 0 => array('token' => _Include::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_EQUAL' )),
        );
        
        $this->actions = array('transform'    => array(1 => 'ARGUMENTS',),
                               'atom'         => 'Include',
                               'addSemicolon' => 'it',
                               'property'     => array('parenthesis' => false),);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.getProperty('parenthesis') == true) {
    fullcode.setProperty('fullcode', fullcode.getProperty('code') + "(" + fullcode.out("ARGUMENTS").next().getProperty('fullcode') + ")");
} else {
    s = fullcode.out("ARGUMENTS").next().getProperty('fullcode');
    fullcode.setProperty('fullcode', it.getProperty('code') + " " + s );
}

GREMLIN;
    }

}
?>
