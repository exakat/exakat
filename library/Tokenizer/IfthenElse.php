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

class IfthenElse extends TokenAuto {
    public static $operators = array('T_ELSE');
    
    public function _check() {
        // @doc else : endif (empty )
        $this->conditions = array( 0 => array('token' => 'T_ELSE'),
                                   1 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   2 => array('token' => 'T_ENDIF'),
        );
        
        $this->actions = array('insertVoid'  => 1,
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto();

        // else 1 instruction, no {}
        $this->conditions = array(  0 => array('token'   => IfthenElse::$operators,
                                               'atom'    => 'none'),
                                    1 => array('notAtom' => 'Sequence',
                                               'atom'    => 'yes'),
                                    2 => array('filterOut' => Token::$instructionEnding),
        );
        
        $this->actions = array( 'toBlockElse' => true);
        $this->checkAuto();

        return false;
    }
    
    // fullcode is done in Ifthen
}

?>
