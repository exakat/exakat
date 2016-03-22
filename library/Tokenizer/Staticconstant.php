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

class Staticconstant extends TokenAuto {
    static public $operators = array('T_DOUBLE_COLON');
    static public $atom = 'Staticconstant';

    public function _check() {
        $this->conditions = array( -1 => array('atom'      => Staticproperty::$operands),
                                    0 => array('token'     => Staticconstant::$operators),
                                    1 => array('token'     => array_merge(Constant::$operators, _Include::$operators,
                                                                          _Dowhile::$operators,  _While::$operators,
                                                                          _Const::$operators,    _For::$operators,
                                                                          _Foreach::$operators)),
                                    2 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS')),
                                 );
        
        $this->actions = array('transform'    => array( -1 => 'CLASS',
                                                         1 => 'CONSTANT'),
                               'atom'         => 'Staticconstant',
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it' );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.out('CONSTANT').has('fullcode', null).each{ it.fullcode = it.code; }
fullcode.setProperty('fullcode',  it.out("CLASS").next().getProperty('fullcode') + "::" + it.out("CONSTANT").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
