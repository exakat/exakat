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

class _Catch extends TokenAuto {
    static public $operators = array('T_CATCH');
    static public $atom = 'Catch';

    public function _check() {
        // non-empty catch
        $this->conditions = array(0 => array('token'    => _Catch::$operators,
                                             'atom'     => 'none'),
                                  1 => array('token'    => 'T_OPEN_PARENTHESIS',
                                             'property' => array('association' => 'Catch')),
                                  2 => array('atom'     => array('Identifier', 'Nsname')),
                                  3 => array('atom'     => 'Variable'),
                                  4 => array('token'    => 'T_CLOSE_PARENTHESIS'),
                                  5 => array('token'    => 'T_OPEN_CURLY',
                                             'property' => array('association' => 'Catch')),
                                  6 => array('atom'     => array('Sequence', 'Void')),
                                  7 => array('token'    => 'T_CLOSE_CURLY'),
                                  );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'CLASS',
                                                       3 => 'VARIABLE',
                                                       4 => 'DROP',
                                                       5 => 'DROP',
                                                       6 => 'CODE',
                                                       7 => 'DROP',
                                                       ),
                               'cleanIndex' => true,
                               'atom'       => 'Catch');
                               
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
fullcode.setProperty('fullcode', "catch (" + fullcode.out("CLASS").next().getProperty('fullcode') + " " + fullcode.out("VARIABLE").next().getProperty('fullcode') + ") " + fullcode.out("CODE").next().getProperty('fullcode'));
GREMLIN;
    }
}

?>
