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

class _Dowhile extends TokenAuto {
    static public $operators = array('T_DO');
    static public $atom = 'Dowhile';

    public function _check() {
        // do ; while() (no block...)
        $this->conditions = array( 0 => array('token'   => _Dowhile::$operators),
                                   1 => array('atom'    => 'yes'),
                                   2 => array('token'   => 'T_SEMICOLON',
                                              'atom'    => 'none'),
                                   3 => array('token'   => 'T_WHILE',
                                              'dowhile' => true),
                                   4 => array('token'   => 'T_OPEN_PARENTHESIS'),
                                   5 => array('atom'    => 'yes'),
                                   6 => array('token'   => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('transform'    => array(   1 => 'BLOCK',
                                                          2 => 'DROP',
                                                          3 => 'DROP',
                                                          4 => 'DROP',
                                                          5 => 'CONDITION',
                                                          6 => 'DROP'
                                                        ),
                               'atom'         => 'Dowhile',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // do if() {} while() (no block...)
        $this->conditions = array( 0 => array('token'   => _Dowhile::$operators),
                                   1 => array('atom'    => 'yes',
                                              'notAtom' => 'Sequence'),
                                   2 => array('token'   => 'T_WHILE',
                                              'dowhile' => true),
                                   3 => array('token'   => 'T_OPEN_PARENTHESIS'),
                                   4 => array('atom'    => 'yes'),
                                   5 => array('token'   => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('transform'    => array(   1 => 'BLOCK',
                                                          2 => 'DROP',
                                                          3 => 'DROP',
                                                          4 => 'CONDITION',
                                                          5 => 'DROP'
                                                        ),
                               'atom'         => 'Dowhile',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // do { block } while()
        $this->conditions = array( 0 => array('token' => _Dowhile::$operators),
                                   1 => array('atom'  => 'Sequence'),
                                   2 => array('token' => 'T_WHILE',
                                              'dowhile' => true),
                                   3 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   4 => array('atom'  => 'yes'),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('transform'    => array(   1 => 'BLOCK',
                                                          2 => 'DROP',
                                                          3 => 'DROP',
                                                          4 => 'CONDITION',
                                                          5 => 'DROP'
                                                        ),
                               'atom'         => 'Dowhile',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', "do " + fullcode.out("BLOCK").next().getProperty('fullcode') + " while (" + fullcode.out("CONDITION").next().getProperty('fullcode') + ")");

GREMLIN;

    }
}

?>
