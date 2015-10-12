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

class _Interface extends TokenAuto {
    static public $operators = array('T_INTERFACE');
    static public $atom = 'Interface';

    public function _check() {
        // interface x {} Get the name
        $this->conditions = array( 0 => array('token' => static::$operators),
                                   1 => array('atom'  => array('Identifier', 'Null', 'Boolean'))
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'NAME'),
                               'keepIndexed' => true,
                               'atom'        => 'Class',
                               'cleanIndex'  => true);
        $this->checkAuto();

    // interface x implements a {} get the implements
        $this->conditions = array( 0 => array('token'     => static::$operators),
                                   1 => array('token'     => 'T_EXTENDS',
                                              'checkForImplements' => array('Identifier', 'Nsname')),
                                   2 => array('atom'      => array('Identifier', 'Nsname')),
                                   3 => array('token'     => array('T_COMMA', 'T_OPEN_CURLY'))
                                 );
        
        $this->actions = array('toImplements' => 'EXTENDS',
                               'keepIndexed'  => true,
                               'cleanIndex'   => true
                               );
        $this->checkAuto();

    // class x { // some real code} get the block
        $this->conditions = array( 0 => array('token'    => static::$operators),
                                   1 => array('token'    => 'T_OPEN_CURLY',
                                              'property' => array('association' => 'Interface')),
                                   2 => array('atom'     => array('Sequence', 'Void')),
                                   3 => array('token'    => 'T_CLOSE_CURLY')
                                  );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'BLOCK',
                                                       3 => 'DROP'),
                               'atom'         => 'Interface',
                               'addSemicolon' => 'it',
                               'makeBlock'    => 'BLOCK',
                               'cleanIndex'   => true
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "interface " + fullcode.out("NAME").next().code;

// extends
if (fullcode.out("EXTENDS").count() > 0) {
    s = [];
    fullcode.out("EXTENDS").sort{it.rank}._().each{ s.add(it.fullcode); };
    fullcode.fullcode = fullcode.fullcode + " extends " + s.join(", ");
}

GREMLIN;
    }

}

?>
