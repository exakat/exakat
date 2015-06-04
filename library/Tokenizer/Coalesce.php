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

class Coalesce extends TokenAuto {
    static public $operators = array('T_COALESCE');
    static public $atom = 'Coalesce';
    
    protected $phpVersion = '7.0+';

    public function _check() {
        // logical boolean (and, or)
        $this->conditions = array( -1 => array('atom'     => 'yes'),
                                    0 => array('token'    => Coalesce::$operators,
                                               'atom'     => 'none'),
                                    1 => array('atom'     => 'yes',
                                               'notAtom'  => 'Sequence'),
                                    2 => array('notToken' => array_merge(Coalesce::$operators, Parenthesis::$operators)),
                                   );
        
        $this->actions = array('transform'    => array( -1 => 'LEFT',
                                                         1 => 'RIGHT'),
                               'atom'         => 'Coalesce',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
        
fullcode.fullcode = fullcode.out("LEFT").next().fullcode + " " + fullcode.code + " " + it.out("RIGHT").next().fullcode;

GREMLIN;
    }
}
?>
