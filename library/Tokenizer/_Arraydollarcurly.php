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

class _Arraydollarcurly extends TokenAuto {
    static public $operators = array('T_DOLLAR_OPEN_CURLY_BRACES');
    static public $atom = 'Array';
    
    public function _check() {
        $this->conditions = array( 0 => array('token' => _Arraydollarcurly::$operators),
                                   1 => array('token' => 'T_STRING_VARNAME'),
                                   2 => array('token' => 'T_OPEN_BRACKET'),
                                   3 => array('token' => 'T_CONSTANT_ENCAPSED_STRING'),
                                   4 => array('token' => 'T_CLOSE_BRACKET'),
                                   5 => array('token' => 'T_CLOSE_CURLY'),
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'VARIABLE',
                                                         2 => 'DROP',
                                                         3 => 'INDEX',
                                                         4 => 'DROP',
                                                         5 => 'DROP'  ),
                               'atom'        => 'Array',
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto();

        return false;
    }
}

?>
