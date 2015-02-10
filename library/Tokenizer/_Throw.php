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

class _Throw extends TokenAuto {
    static public $operators = array('T_THROW');
    static public $atom = 'Throw';
    
    public function _check() {
        $this->conditions = array(0 => array('token'     => _Throw::$operators,
                                             'atom'      => 'none'),
                                  1 => array('atom'      => array('New', 'Variable', 'Functioncall', 'Property', 'Array', 'Methodcall',
                                                                  'Staticmethodcall', 'Staticproperty', 'Identifier', 'Assignation', 'Ternary')),
                                  2 => array('filterOut' => Token::$instructionEnding),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'THROW'),
                               'atom'         => 'Throw',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
                               
        $this->checkAuto();

        $this->conditions = array(0 => array('token' => _Throw::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => array('New', 'Variable', 'Functioncall', 'Property', 'Array', 'Methodcall',
                                                              'Staticmethodcall', 'Staticproperty', 'Identifier', 'Assignation')),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'THROW',
                                                        3 => 'DROP',),
                               'atom'         => 'Throw',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
                               
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "throw " + fullcode.out("THROW").next().fullcode;
GREMLIN;
    }
}

?>
