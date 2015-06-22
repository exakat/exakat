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

class _Yield extends TokenAuto {
    static public $operators = array('T_YIELD');
    static public $atom = 'Yield';

    public function _check() {
        $this->conditions = array(0 => array('token' => _Yield::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => 'yes'),
                                  2 => array('token' => array('T_SEMICOLON', 'T_CLOSE_PARENTHESIS', 'T_CLOSE_TAG'))
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'YIELD' ),
                               'cleanIndex'   => true,
                               'atom'         => 'Yield',
                               'addSemicolon' => 'it');
                               
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
fullcode.setProperty('fullcode', "yield " + fullcode.out("YIELD").next().getProperty('fullcode'));
GREMLIN;
    }
}

?>
