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

class Keyvalue extends TokenAuto {
    static public $operators = array('T_DOUBLE_ARROW');
    static public $atom = 'Keyvalue';

    public function _check() {
        $this->conditions = array(-2 => array('token' => array('T_OPEN_PARENTHESIS', 'T_COMMA', 'T_AS', 'T_OPEN_BRACKET', 'T_YIELD')),
                                  -1 => array('atom' => 'yes',
                                              'notAtom' => 'Arguments'),
                                   0 => array('token' => Keyvalue::$operators),
                                   1 => array('atom' => 'yes',
                                              'notAtom' => 'Arguments'),
                                   2 => array('token' => array('T_CLOSE_PARENTHESIS', 'T_COMMA', 'T_CLOSE_BRACKET', 'T_SEMICOLON')),
                                  );
        
        $this->actions = array('transform'  => array(-1 => 'KEY',
                                                      1 => 'VALUE'),
                               'atom'       => 'Keyvalue',
                               'cleanIndex' => true);
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("KEY").next().getProperty('fullcode') + " => " + fullcode.out("VALUE").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
