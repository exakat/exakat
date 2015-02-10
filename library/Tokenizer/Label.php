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

class Label extends TokenAuto {
    static public $operators = array('T_COLON');
    static public $atom = 'Label';
    
    public function _check() {
        $this->conditions = array(-2 => array('filterOut' => array_merge(array('T_QUESTION', 'T_CASE', 'T_DOT', 'T_NS_SEPARATOR',
                                                                               'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_NEW',
                                                                               'T_INSTANCEOF', 'T_COLON'),
                                                                         Assignation::$operators, Addition::$operators,
                                                                         Multiplication::$operators, Comparison::$operators,
                                                                         Logical::$operators, Not::$operators,
                                                                         Cast::$operators)),
                                  -1 => array('atom'      => 'Identifier'),
                                   0 => array('token'     => Label::$operators));
        
        $this->actions = array('transform'    => array(-1 => 'LABEL'),
                               'atom'         => 'Label',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // special case for default : Identifier : 
        $this->conditions = array(-3 => array('token' => 'T_DEFAULT'),
                                  -2 => array('token' => 'T_COLON'),
                                  -1 => array('atom'  => 'Identifier'),
                                   0 => array('token' => Label::$operators));
        
        $this->actions = array('transform'    => array(-1 => 'LABEL'),
                               'atom'         => 'Label',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // special case for case <atom> : Identifier : 
        $this->conditions = array(-4 => array('token' => 'T_CASE'),
                                  -3 => array('atom'  => 'yes'),
                                  -2 => array('token' => 'T_COLON'),
                                  -1 => array('atom'  => 'Identifier'),
                                   0 => array('token' => Label::$operators));
        
        $this->actions = array('transform'    => array(-1 => 'LABEL'),
                               'atom'         => 'Label',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }


    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = fullcode.out('LABEL').next().fullcode + ' : ';

GREMLIN;
    }
}

?>
