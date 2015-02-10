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

class _Array extends TokenAuto {
    static public $operators = array('T_OPEN_BRACKET', 'T_OPEN_CURLY');
    static public $atom = 'Array';
    static public $allowedObject = array('Variable', 'Array', 'Property', 'Staticproperty', 'Arrayappend',
                                          'Functioncall', 'Methodcall', 'Staticmethodcall', 'String');
    
    public function _check() {
        // $x[3] (and keep the indexation for doing it again, or with FunctioncallArray);
        $this->conditions = array( -1 => array('atom'  => _Array::$allowedObject),
                                    0 => array('token' => _Array::$operators),
                                    1 => array('atom'  => 'yes',
                                               'notAtom' => 'Sequence'),
                                    2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                    3 => array('token' => array('T_OPEN_PARENTHESIS','T_OPEN_BRACKET', 'T_OPEN_CURLY')),
                                 );
        
        $this->actions = array('transform'    => array(  -1 => 'VARIABLE',
                                                          1 => 'INDEX',
                                                          2 => 'DROP'),
                               'atom'         => 'Array',
                               'cleanIndex'   => true,
                               'addToIndex'   => array('S_ARRAY' => 'S_ARRAY')
                               );
        $this->checkAuto();

        // $x[3] (and stop the indexation
        $this->conditions = array( -1 => array('atom'  => _Array::$allowedObject),
                                    0 => array('token' => _Array::$operators),
                                    1 => array('atom'  => 'yes',
                                               'notAtom' => 'Sequence'),
                                    2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                    3 => array('notToken' => array('T_OPEN_PARENTHESIS','T_OPEN_BRACKET', 'T_OPEN_CURLY')),
                                 );
        
        $this->actions = array('transform'    => array(  -1 => 'VARIABLE',
                                                          1 => 'INDEX',
                                                          2 => 'DROP'),
                               'atom'         => 'Array',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               );
        $this->checkAuto();

        // $x[3] // will loop for each dimension
        $this->conditions = array( -1 => array('atom'  => _Array::$allowedObject),
                                    0 => array('token' => _Array::$operators),
                                    1 => array('atom'  => 'yes',
                                               'notAtom' => 'Sequence'),
                                    2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                    3 => array('notToken' => array('T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET', 'T_OPEN_CURLY')),
                                 );
        
        $this->actions = array('transform'    => array(  -1 => 'VARIABLE',
                                                          1 => 'INDEX',
                                                          2 => 'DROP'),
                               'atom'         => 'Array',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.out("NAME").each { fullcode.setProperty('fullcode', fullcode.getProperty('fullcode')); }

if (fullcode.code == '[') {
    fullcode.filter{ it.out("INDEX").count() == 1}.each{ fullcode.setProperty('fullcode', it.out("VARIABLE").next().getProperty('fullcode') + "[" + it.out("INDEX").next().getProperty('fullcode') + "]"); }
} else {
    fullcode.filter{ it.out("INDEX").count() == 1}.each{ fullcode.setProperty('fullcode', it.out("VARIABLE").next().getProperty('fullcode') + "{" + it.out("INDEX").next().getProperty('fullcode') + "}"); }
}

GREMLIN;
    }
}

?>
