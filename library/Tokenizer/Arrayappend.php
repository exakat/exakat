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

class Arrayappend extends TokenAuto {
    static public $operators = array('T_OPEN_BRACKET');
    static public $atom = 'Arrayappend';
    
    public function _check() {
        $this->conditions = array(-2 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OBJECT_OPERATOR')),
                                  -1 => array('atom' => array('Variable', 'Property', 'Staticproperty', 'Array', 'Arrayappend')),
                                   0 => array('token' => Arrayappend::$operators),
                                   1 => array('token' => 'T_CLOSE_BRACKET'),
        );
        
        $this->actions = array('transform'  => array(  -1 => 'VARIABLE',
                                                        1 => 'DROP'),
                               'atom'       => 'Arrayappend',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.out("VARIABLE").next().getProperty('fullcode') + "[]");

GREMLIN;
    }
}

?>
