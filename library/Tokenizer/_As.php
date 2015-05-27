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

class _As extends TokenAuto {
    static public $operators = array('T_AS');
    static public $atom = 'As';

    public function _check() {
        // use A as B (adds rank)
        $this->conditions = array( -2 => array('notToken' => array('T_NS_SEPARATOR', 'T_DOUBLE_COLON')),
                                   -1 => array('atom'     => 'Identifier'),
                                    0 => array('token'    => _As::$operators,
                                               'atom'     => 'none'),
                                    1 => array('token'    => array('T_STRING', 'T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE'))
        );
        
        $this->actions = array('transform'    => array( 1 => 'AS',
                                                       -1 => 'SUBNAME'),
                               'atom'         => 'As',
                               'cleanIndex'   => true,
                               'rank'         => array(-1 => '0'));
        $this->checkAuto();

        // use C::Const as string
        $this->conditions = array( -1 => array('atom'  => 'Staticconstant'),
                                    0 => array('token' => self::$operators,
                                               'atom'  => 'none'),
                                    1 => array('token' => 'T_STRING')
        );
        
        $this->actions = array('transform'    => array( 1 => 'RIGHT',
                                                       -1 => 'LEFT'),
                               'atom'         => 'As',
                               'cleanIndex'   => true,
//                               'addSemicolon' => 'it' 
                               );
        $this->checkAuto();
        
        return false;
    }
    
    public function fullcode() {
        return <<<GREMLIN

if (fullcode.out('SUBNAME').any()) {
    s = [];
    fullcode.out("SUBNAME").sort{it.rank}._().each{
        s.add(it.getProperty('code'));
    };
    if (fullcode.absolutens == true) {
        s =  '\\\\' + s.join('\\\\');
    } else {
        s = s.join('\\\\');
    }

    fullcode.setProperty('fullcode', s + " as " + fullcode.out("AS").next().getProperty('fullcode'));
    fullcode.out('AS').filter{ it.token in [ 'T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE']}.each{
        it.setProperty('fullcode', it.code);
        it.setProperty('atom', 'Ppp');
    }
} else {
    fullcode.setProperty('fullcode', fullcode.out('LEFT').next().fullcode + ' as ' + fullcode.out('RIGHT').next().fullcode);
}


GREMLIN;
    }
}
?>
