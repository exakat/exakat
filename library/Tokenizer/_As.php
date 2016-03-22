<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
        // use A as B
        // use C::Const as string
        $this->conditions = array( -2 => array('notToken' => 'T_DOUBLE_COLON'),
                                   -1 => array('atom'     => array('Staticconstant', 'Identifier', 'Nsname')),
                                    0 => array('token'    => _As::$operators,
                                               'atom'     => 'none'),
                                    1 => array('token'    => 'T_STRING')
        );
        
        $this->actions = array('transform'    => array( 1 => 'AS',
                                                       -1 => 'NAME'),
                               'atom'         => 'As',
                               'cleanIndex'   => true,
                               'rank'         => array(-1 => '0'));
        $this->checkAuto();

        // use A as public;
        $this->conditions = array( -2 => array('notToken' => 'T_DOUBLE_COLON'),
                                   -1 => array('atom'     => 'Identifier'),
                                    0 => array('token'    => _As::$operators,
                                               'atom'     => 'none'),
                                    1 => array('token'    => array('T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE')),
                                    2 => array('notToken' => 'T_STRING'),
        );
        
        //  A as public;
        $this->actions = array('transform'    => array( 1 => 'VISIBILITY',
                                                       -1 => 'NAME'),
                               'atom'         => 'As',
                               'cleanIndex'   => true,
                               'rank'         => array(-1 => '0')
                               );
        $this->checkAuto();

        // use A as B private
        $this->conditions = array( -2 => array('notToken' => 'T_DOUBLE_COLON'),
                                   -1 => array('atom'     => 'Identifier'),
                                    0 => array('token'    => _As::$operators,
                                               'atom'     => 'none'),
                                    1 => array('token'    => array('T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE')),
                                    2 => array('token'    => 'T_STRING'),
        );
        
        $this->actions = array('transform'    => array( 2 => 'VISIBILITY',
                                                        1 => 'AS',
                                                       -1 => 'NAME'),
                               'atom'         => 'As',
                               'cleanIndex'   => true,
                               'rank'         => array(-1 => '0'));
        $this->checkAuto();
        
        return false;
    }
    
    public function fullcode() {
        return <<<GREMLIN

theVisibility = '';
fullcode.out('VISIBILITY').each{
    it.setProperty('fullcode', it.code);
    it.setProperty('atom', 'Visibility');
    theVisibility = fullcode.out('VISIBILITY').next().fullcode + ' ';
}

if (fullcode.out('NAME').any()) {
    theName = fullcode.out('NAME').next().fullcode;
} else {
    theName = '';
}

theAs = '';
if (fullcode.out('AS').any()) {
    theAs = fullcode.out("AS").next().getProperty('fullcode')
}

fullcode.setProperty('fullcode', theName + " as " + theVisibility + theAs);

GREMLIN;
    }
}
?>
