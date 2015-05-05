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

class Shell extends TokenAuto {
    static public $operators = array('T_SHELL_QUOTE');
    static public $atom = 'Shell';

    public function _check() {
// Case of string with interpolation : `a${b}c`;
        $this->conditions = array(  0 => array('token'            => Shell::$operators,
                                               'atom'             => 'none'),
                                    1 => array('atom'             => String::$allowedClasses,
                                               'check_for_string' => String::$allowedClasses),
                                 );
        
        $this->actions = array( 'makeQuotedString' => 'Shell',
                                'makeSequence'     => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        // fullcode is not meant to reproduce the whole code, but give a quick peek at some smaller code. Just ignoring for the moment.
        return <<<GREMLIN

s = [];
fullcode.out('CONCAT').sort{it.rank}._().each{ s.add(it.fullcode); }
fullcode.setProperty('fullcode', '`' + s.join('') + '`');

GREMLIN;
    }
}
?>
