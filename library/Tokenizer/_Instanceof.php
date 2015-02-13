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

class _Instanceof extends TokenAuto {
    static public $operators = array('T_INSTANCEOF');
    static public $atom = 'Instanceof';

    public function _check() {
        $this->conditions = array(-2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_NEW')),
                                  -1 => array('atom'      => 'yes',
                                              'notAtom'   => 'Sequence'),
                                   0 => array('token'     => _Instanceof::$operators,
                                              'atom'      => 'none'),
                                   1 => array('atom'      => 'yes',
                                              'notAtom'   => 'Sequence'),
                                   2 => array('filterOut' => array('T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 
                                                                   'T_DOUBLE_COLON', 'T_NS_SEPARATOR')),
                                  
        );
        
        $this->actions = array('transform'    => array(  1 => 'CLASS',
                                                        -1 => 'VARIABLE'),
                               'atom'         => 'Instanceof',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it' );
        $this->checkAuto();
        
        return false;
    }
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("VARIABLE").next().getProperty('fullcode') + " instanceof " + fullcode.out("CLASS").next().getProperty('fullcode'));

GREMLIN;

    }
}
?>
