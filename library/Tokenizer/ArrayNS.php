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

class ArrayNS extends TokenAuto {
    static public $operators = array('T_OPEN_BRACKET');
    static public $atom = 'Array';
    
    protected $phpVersion = '5.4+';
    
    public function _check() {
        $yields =  array('T_VARIABLE', 'T_CLOSE_BRACKET', 'T_STRING', 'T_OBJECT_OPERATOR',
                         'T_DOLLAR', 'T_DOUBLE_COLON', 'T_OPEN_CURLY', 'T_CLOSE_CURLY',
                         'T_CLOSE_PARENTHESIS' );

        // [ arguments ] : prepare arguments
        $this->conditions = array(-1 => array('filterOut2' => $yields,
                                              'notAtom'    => array('Parenthesis', 'Array', 'Arrayappend')),
                                   0 => array('token'      => ArrayNS::$operators),
                                   1 => array('atom'       => 'yes',
                                              'notAtom'    => 'Arguments'),
                                   2 => array('token'      => 'T_CLOSE_BRACKET'),
        );
        
        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENTS')),
                               'keepIndexed' => true);
        $this->checkAuto();

        // [ ] empty array
        $this->conditions = array(-1 => array('filterOut2' => $yields,
                                              'notAtom'    => array('Parenthesis', 'Array', 'Arrayappend')),
                                   0 => array('token' => ArrayNS::$operators),
                                   1 => array('token' => 'T_CLOSE_BRACKET'),
        );
        
        $this->actions = array('addEdge'     => array(0 => array('Void' => 'ARGUMENTS')),
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto();

        // [ ] non-empty array
        $this->conditions = array(-1 => array('filterOut2' => $yields,
                                              'notAtom'    => array('Parenthesis', 'Array', 'Arrayappend')),
                                   0 => array('token'      => ArrayNS::$operators),
                                   1 => array('atom'       => 'Arguments'),
                                   2 => array('token'      => 'T_CLOSE_BRACKET'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'ARGUMENTS',
                                                        2 => 'DROP'),
                               'atom'         => 'Array',
                               'property'     => array('short_syntax' => 'true'),
                               'cleanIndex'   => true);
        $this->checkAuto();

        return false;
    }
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', "[ " + fullcode.out('ARGUMENTS').next().getProperty('fullcode') + " ]");

GREMLIN;
    }

}

?>
