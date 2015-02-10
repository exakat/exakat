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

class Staticmethodcall extends TokenAuto {
    static public $operators = array('T_DOUBLE_COLON');
    static public $atom = 'Staticmethodcall';

    public function _check() {
        $operands = array('Constant', 'Identifier', 'Variable', 'Array', 'Nsname', 'Static', 'Nsname',);

        // unusual call : Class::{Method}(); Only build the Functioncall
        $this->conditions = array( -2 => array('filterOut2' => array('T_NS_SEPARATOR')),
                                   -1 => array('atom'       => $operands),
                                    0 => array('token'      => Staticmethodcall::$operators),
                                    1 => array('token'      => 'T_OPEN_CURLY'),
                                    2 => array('atom'       => 'yes'),
                                    3 => array('token'      => 'T_CLOSE_CURLY'),
                                    4 => array('token'      => 'T_OPEN_PARENTHESIS'),
                                    5 => array('atom'       => array('Arguments', 'Void')),
                                    6 => array('token'      => 'T_CLOSE_PARENTHESIS'),
                                 );

        $this->actions = array('to_specialmethodcall' => true,
                               'makeSequence'         => 'it',
                               'cleanIndex'           => true);
        $this->checkAuto();

        // normal call : Class::Method();
        $this->conditions = array( -2 => array('filterOut2' => array('T_NS_SEPARATOR')),
                                   -1 => array('atom' => $operands),
                                    0 => array('token' => Staticmethodcall::$operators),
                                    1 => array('atom' => array('Functioncall', 'Methodcall')),
                                 );
        
        $this->actions = array('transform'    => array( -1 => 'CLASS',
                                                         1 => 'METHOD'),
                               'makeSequence' => 'it',
                               'atom'         => 'Staticmethodcall',
                               'cleanIndex'   => true);
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

methode = fullcode.out("METHOD").next().getProperty('fullcode');
if (fullcode.out("METHOD").next().getProperty('block') == true) {
    methode = "{" + methode + "}";
}

fullcode.setProperty('fullcode', fullcode.out("CLASS").next().getProperty('fullcode') + "::" + methode);

GREMLIN;
    }
}

?>
