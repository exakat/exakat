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

class _Switch extends TokenAuto {
    static public $operators = array('T_SWITCH');
    static public $atom = 'Switch';

    public function _check() {
        // switch ( $cdt ) Block
        $this->conditions = array(0 => array('token' => _Switch::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS',
                                             'property' => array('association' => 'Switch')),
                                  2 => array('atom'  => 'yes'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token' => 'T_OPEN_CURLY'),
                                  5 => array('atom'  => array('SequenceCaseDefault', 'Void')),
                                  6 => array('token' => 'T_CLOSE_CURLY')
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'NAME',
                                                        3 => 'DROP',
                                                        4 => 'DROP',
                                                        5 => 'CASES',
                                                        6 => 'DROP'),
                               'atom'         => 'Switch',
                               'makeBlock'    => 'CASES',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // alternative syntax
        $this->conditions = array(0 => array('token'    => _Switch::$operators,
                                             'atom'     => 'none'),
                                  1 => array('token'    => 'T_OPEN_PARENTHESIS',
                                             'property' => array('association' => 'Switch')),
                                  2 => array('atom'     => 'yes'),
                                  3 => array('token'    => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token'    => 'T_COLON',
                                             'property' => array('relatedAtom' => 'Switch')),
                                  5 => array('atom'     => array('SequenceCaseDefault', 'Void')),
                                  6 => array('token'    => 'T_ENDSWITCH'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'NAME',
                                                        3 => 'DROP',
                                                        4 => 'DROP',
                                                        5 => 'CASES',
                                                        6 => 'DROP',),
                               'atom'         => 'Switch',
                               'property'     => array('alternative' => true),
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (it.alternative == true) {
    it.setProperty('fullcode', "switch (" + it.out("NAME").next().fullcode + ") : " + it.out("CASES").next().getProperty('fullcode') + ' endswitch');
} else {
    it.setProperty('fullcode', "switch (" + it.out("NAME").next().fullcode + ") " + it.out("CASES").next().getProperty('fullcode'));
}

GREMLIN;
    }
}

?>
