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

class Methodcall extends TokenAuto {
    static public $operators = array('T_OBJECT_OPERATOR');
    static public $atom = 'Methodcall';

    public function _check() {
        $operands = array('Variable', 'Property', 'Array', 'Functioncall', 'Methodcall',
                          'Staticmethodcall', 'Staticproperty', 'Parenthesis' );

        // $this->{$x}($args);
        $this->conditions = array( -2 => array('filterOut' => array('T_DOUBLE_COLON', 'T_DOLLAR')),
                                   -1 => array('atom'      => $operands),
                                    0 => array('token'     => Methodcall::$operators),
                                    1 => array('token'     => 'T_OPEN_CURLY'),
                                    2 => array('atom'      => 'yes'),
                                    3 => array('token'     => 'T_CLOSE_CURLY'),
                                    4 => array('token'     => 'T_OPEN_PARENTHESIS'),
                                    5 => array('atom'      => array('Arguments', 'Void')),
                                    6 => array('token'     => 'T_CLOSE_PARENTHESIS')
                                 );
        
        $this->actions = array('to_functioncall' => true,
                               'cleanIndex'      => true,
                               'keepIndexed'     => true);
        $this->checkAuto();

        // $this->x($args);
        $this->conditions = array( -2 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_DOLLAR')),
                                   -1 => array('atom'      => $operands),
                                    0 => array('token'     => Methodcall::$operators,
                                               'atom'      => 'none'),
                                    1 => array('atom'      => 'Functioncall')
                                 );
        
        $this->actions = array('addSemicolon'  => 'b1',
                               'to_methodcall' => true,
                               'cleanIndex'    => true,
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.out("NAME").each{ it.setProperty('fullcode', it.getProperty('code')); };

fullcode.filter{ it.out("METHOD").count() == 1}.each{ fullcode.fullcode = fullcode.out("OBJECT").next().getProperty('fullcode') + "->" + fullcode.out("METHOD").next().getProperty('fullcode');  }

GREMLIN;
    }
}

?>
