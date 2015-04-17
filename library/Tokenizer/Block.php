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

class Block extends TokenAuto {
    static public $operators = array('T_OPEN_CURLY');
    static public $atom = 'Sequence';
    
    public function _check() {
    // @doc {{ Block}}
        $this->conditions = array( -1 => array('token'   => array('T_OPEN_CURLY')),
                                    0 => array('token'   => self::$operators),
                                    1 => array('atom'    => 'yes'),
                                    2 => array('token'   => 'T_CLOSE_CURLY',
                                               'atom'    => 'none'),
                                    3 => array('token'   => array('T_CLOSE_CURLY', 'T_SEMICOLON', 'T_OPEN_CURLY')),
        );
        
        $this->actions = array('to_block'     => true,
                               'atom'         => 'Sequence',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               'property'     => array('bracket' => true)
                               );
        $this->checkAuto();

    // @doc Block
        $this->conditions = array( -1 => array('filterOut2' => array('T_VARIABLE', 'T_DOLLAR',
                                                                     'T_CLOSE_CURLY', 'T_OPEN_CURLY',// $x{1}{3},
                                                                     'T_CLOSE_PARENTHESIS', 'T_OPEN_PARENTHESIS',// x(1){3},
                                                                     'T_OPEN_BRACKET', 'T_CLOSE_BRACKET',  // $x[1]{3},
                                                                     'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_AT',
                                                                     'T_STRING', 'T_COMMA')),
                                    0 => array('token'      => self::$operators,
                                               'property'   => array('association' => 'none')),
                                    1 => array('atom'       => 'yes'),
                                    2 => array('token'      => 'T_CLOSE_CURLY',
                                               'atom'       => 'none'),
        );

        $this->actions = array('to_block'     => true,
                               'atom'         => 'Sequence',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               'property'     => array('bracket' => true)
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.getProperty('bracket')) {
    fullcode.setProperty('fullcode', "{ /**/ } ");
} else {
    fullcode.setProperty('fullcode', " /**/ ");
}

GREMLIN;
    }
}

?>
