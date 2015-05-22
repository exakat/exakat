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

class _Continue extends TokenAuto {
    static public $operators = array('T_CONTINUE');
    static public $atom = 'Continue';

    public function _check() {
        // continue ; without nothing behind
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => array('T_SEMICOLON', 'T_ENDIF'))
                                  );
        
        $this->actions = array('addEdge'     => array(0 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true);
        $this->checkAuto();

        // continue 2 ;
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer', 'Void'))
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'LEVEL'),
                               'atom'       => 'Continue',
                               'addSemicolon' => 'it');
        $this->checkAuto();

        // continue(2);
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'Integer'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'DROP',
                                                      2 => 'LEVEL',
                                                      3 => 'DROP'),
                               'atom'       => 'Continue',
                               'addSemicolon' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', "continue " + fullcode.out("LEVEL").next().getProperty('code'));

GREMLIN;
    }
}

?>
