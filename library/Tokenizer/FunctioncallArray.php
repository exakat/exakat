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

class FunctioncallArray extends TokenAuto {
    static public $operators = array('S_ARRAY');
    static public $atom = 'Functioncall';

    public function _check() {
        // $x[3]()
        $this->conditions = array(   0 => array('atom'  => 'Array'),
                                     1 => array('atom'  => 'none',
                                                'token' => 'T_OPEN_PARENTHESIS' ),
                                     2 => array('atom'  =>  array('Arguments', 'Void')),
                                     3 => array('atom'  => 'none',
                                                'token' => 'T_CLOSE_PARENTHESIS'),
        );

        $this->actions = array('transform'           => array(1 => 'DROP',
                                                              2 => 'ARGUMENTS',
                                                              3 => 'DROP'),
                               'arrayToFunctioncall' => 1,
                               'atom'                => 'Functioncall',
                               'makeSequence'        => 'it'
                               );
        $this->checkAuto();

        // $x[3]()
        $this->conditions = array(   0 => array('atom'     => 'Array'),
                                     1 => array('notToken' => array('T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET')));

        $this->actions = array('cleanIndex' => 'yes');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out('VARIABLE').next().getProperty('fullcode') + "[" +
                                 fullcode.out('INDEX').next().getProperty('fullcode')    + "]" +
                                 fullcode.out("ARGUMENTS").next().getProperty('fullcode'));

// count the number of arguments
// filter out void ?
fullcode.setProperty("count", fullcode.out("ARGUMENTS").out("ARGUMENT").count());

GREMLIN;
    }


}
?>
