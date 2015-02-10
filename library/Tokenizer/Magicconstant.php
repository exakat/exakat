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

class Magicconstant extends TokenAuto {
    static public $operators = array('T_CLASS_C','T_FUNC_C', 'T_DIR', 'T_FILE', 'T_LINE','T_METHOD_C', 'T_NS_C', 'T_TRAIT_C');
    static public $atom = 'Magicconstant';

    public function _check() {

        $this->conditions = array( 0 => array('token' => Magicconstant::$operators,
                                              'atom'  => 'none'));
        $this->actions = array('atom'       => 'Magicconstant');
        
        return $this->checkAuto();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code'));

GREMLIN;
    }
}

?>
