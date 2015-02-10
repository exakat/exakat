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

class Heredoc extends TokenAuto {
    static public $operators = array('T_START_HEREDOC');
    static public $atom = 'String';
    
    public function _check() {
        $this->conditions = array(0 => array('token'            => Heredoc::$operators,
                                             'atom'             => 'none'),
                                  1 => array('atom'             => String::$allowedClasses,
                                             'check_for_string' => String::$allowedClasses),
                                 );

        $this->actions = array( 'make_quoted_string' => 'Heredoc');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out("CONCAT").sort{it.rank}._().each{ s.add(it.fullcode); };
fullcode.setProperty('noDelimiter', s.join(""));

if (fullcode.in('CONTAIN').next().code.substring(3, 4) in ["'"]) {
    // must get rid of ' in the nowdoc indicator
    fullcode.setProperty('fullcode', it.code + s.join("") + it.code.substring(4, it.code.size() - 2));
    fullcode.setProperty('nowdoc', 'true');
} else {
    fullcode.setProperty('heredoc', 'true');
    fullcode.setProperty('fullcode', it.code + s.join("") + it.code.substring(3, it.code.size()));
}

GREMLIN;
    }
}

?>
