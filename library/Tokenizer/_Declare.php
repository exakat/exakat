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

class _Declare extends TokenAuto {
    static public $operators = array('T_DECLARE');
    static public $atom = 'Declare';

    public function _check() {
        // declare(ticks = 2) : block endblock;
        $this->conditions = array(0 => array('token' => _Declare::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS',
                                             'property' => array('association' => 'Declare')),
                                  2 => array('atom'  => array('Assignation', 'Arguments')),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Declare')),
                                  5 => array('atom'  => 'yes'),
                                  6 => array('token' => 'T_ENDDECLARE'),
        );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'TICKS',
                                                       3 => 'DROP',
                                                       4 => 'DROP',
                                                       5 => 'BLOCK',
                                                       6 => 'DROP',
                                                       ),
                               'atom'         => 'Declare',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // declare(ticks = 2);
        $this->conditions = array(0 => array('token' => _Declare::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS',
                                             'property' => array('association' => 'Declare')),
                                  2 => array('atom'  => array('Assignation', 'Arguments')),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token'  => 'T_SEMICOLON')
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'TICKS',
                                                        3 => 'DROP',
                                                        4 => 'DROP'),
                               'atom'         => 'Declare',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // declare(ticks = 2) { block }
        $this->conditions = array(  0 => array('token' => _Declare::$operators),
                                    1 => array('atom'  => 'none',
                                               'token' => 'T_OPEN_PARENTHESIS',
                                               'property' => array('association' => 'Declare') ),
                                    2 => array('atom'  => array('Assignation', 'Arguments')),
                                    3 => array('atom'  => 'none',
                                               'token' => 'T_CLOSE_PARENTHESIS' ),
                                    4 => array('token' => 'T_OPEN_CURLY'),
                                    5 => array('atom'  => array('Sequence', 'Void')),
                                    6 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'TICKS',
                                                        3 => 'DROP',
                                                        4 => 'DROP',
                                                        5 => 'BLOCK',
                                                        6 => 'DROP'),
                               'atom'         => 'Declare',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "declare (" + fullcode.out('TICKS').next().fullcode + ") ";

fullcode.out('BLOCK').each{ fullcode.fullcode = fullcode.fullcode + it.fullcode; }

GREMLIN;
    }

}

?>
