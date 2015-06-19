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

class _Return extends TokenAuto {
    static public $operators = array('T_RETURN');
    static public $atom = 'Return';

    public function _check() {
        // return with something ;
        $this->conditions = array( 0 => array('token' => _Return::$operators,
                                              'atom'  => 'none' ),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('filterOut2' => array_merge(Token::$instructionEnding,
                                                                          Spaceship::$operators,
                                                                          Coalesce::$operators,
                                                                          array('T_OPEN_CURLY'))),
        );
        
        $this->actions = array('transform'    => array( 1 => 'RETURN'),
                               'atom'         => 'Return',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code') + " " + fullcode.out("RETURN").next().getProperty('fullcode'));

GREMLIN;
    }
}
?>
