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

class Phpcodemiddle extends TokenAuto {
    static public $operators = array('T_INLINE_HTML');

    public function _check() {
// ? >A<?php
        $this->conditions = array(-1 => array('token'    => 'T_CLOSE_TAG',
                                              'atom'     => 'none'),
                                   0 => array('token'    => array_merge(Phpcodemiddle::$operators, 
                                                                        Sequence::$operators)),
                                   1 => array('token'    => 'T_OPEN_TAG',
                                              'atom'     => 'none'),
        );
        $this->actions = array('transform'           => array( -1 => 'DROP',
                                                                1 => 'DROP'),
                               'makeSequence'        => 'it'
                               );
        $this->checkAuto();
        
        return false;
    }
}

?>
