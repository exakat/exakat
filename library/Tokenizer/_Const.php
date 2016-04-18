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

class _Const extends TokenAuto {
    static public $operators = array('T_CONST');
    static public $atom = 'Const';

    public function _check() {
    // class x { const a = 2, b = 2, c = 3; }
    // class x {const a = 2; } only one.
        $this->conditions = array( -1 => array('notToken' => array('T_USE', 'T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE')),
                                    0 => array('token'    =>  self::$operators,
                                               'checkFor' => 'Assignation'),
                                    1 => array('atom'     => 'Assignation'),
                                    2 => array('token'    => array('T_SEMICOLON', 'T_COMMA'))
                                 );
        
        $this->actions = array('makeFromList' => 'CONST',
                               'atom'         => 'Const',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it'
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

s=[];
fullcode.out('CONST').sort{it.rank}._().each{ s.add(it.fullcode);}
fullcode.setProperty('fullcode', 'const ' + s.join(', '));

if (fullcode.out('PRIVATE', 'PROTECTED', 'PUBLIC').any()) {
    fullcode.setProperty('fullcode', fullcode.out('PRIVATE', 'PROTECTED', 'PUBLIC').next().code + 
                                     ' ' + 
                                     fullcode.getProperty('fullcode'));
}

GREMLIN;
    }
}
?>
