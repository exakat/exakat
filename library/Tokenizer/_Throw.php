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
        // throw (new x()) // parenthesis are useless!
        $this->conditions = array(0 => array('token'     => _Throw::$operators,
                                             'atom'      => 'none'),
                                  1 => array('atom'      => array('New', 'Variable', 'Functioncall', 'Property', 'Array',
                                                                  'Methodcall', 'Staticmethodcall', 'Staticproperty',
                                                                  'Identifier', 'Assignation', 'Ternary', 'Parenthesis')),
                                  2 => array('filterOut' => Token::$instructionEnding),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'THROW'),
                               'atom'         => 'Throw',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
                               
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
