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

class VariableDollar extends TokenAuto {
    static public $operators = array('T_DOLLAR');
    static public $atom = 'Variable';
    
    public function _check() {
        // $x or $$x or $$$ (Except for global)
        $this->conditions = array(-1 => array('notToken'  => 'T_GLOBAL'),
                                   0 => array('token'     => VariableDollar::$operators,
                                              'atom'      => 'none'),
                                   1 => array('atom'      => array('Variable', 'Array', 'Property')),
                                   2 => array('filterOut' => array('T_OPEN_BRACKET', 'T_OPEN_CURLY')),
        );
        
        $this->actions = array( 'transform'  => array(1 => 'NAME'),
                                'atom'       => 'Variable',
                                'cleanIndex' => true);
        $this->checkAuto();

        // global $$x->c
        $this->conditions = array(-1 => array('token'     => 'T_GLOBAL'),
                                   0 => array('token'     => VariableDollar::$operators,
                                              'atom'      => 'none'),
                                   1 => array('atom'      => array('Variable', 'Array', 'Property')),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_OPEN_BRACKET', 'T_OPEN_CURLY')),
        );

        $this->actions = array( 'transform'  => array(1 => 'NAME'),
                                'atom'       => 'Variable',
                                'cleanIndex' => true);
        $this->checkAuto();

        // ${x}
        $this->conditions = array(0 => array('token' => VariableDollar::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_OPEN_CURLY'),
                                  2 => array('atom' => 'yes'),
                                  3 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform'  => array(1 => 'DROP',
                                                      2 => 'NAME',
                                                      3 => 'DROP'),
                                'property'   => array('bracket' => true),
                                'atom'       => 'Variable',
                                'cleanIndex' => true);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

name = fullcode.out('NAME').next();
if (fullcode.bracket == true) {
    fullcode.fullcode = "\\\${" + name.fullcode + "}";
} else {
    fullcode.fullcode = "\\\$" + name.fullcode;
}

GREMLIN;

    }
}
?>
