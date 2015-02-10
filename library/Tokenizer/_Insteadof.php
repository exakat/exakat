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

class _Insteadof extends TokenAuto {
    static public $operators = array('T_INSTEADOF');
    static public $atom = 'Insteadof';
    
    protected $phpVersion = "5.4+";

    public function _check() {
        $this->conditions = array( -1 => array('atom'  => 'Staticconstant'),
                                    0 => array('token' => _Insteadof::$operators,
                                               'atom'  => 'none'),
                                    1 => array('token' => 'T_STRING')
        );
        
        $this->actions = array('transform'    => array(  1 => 'RIGHT',
                                                        -1 => 'LEFT'),
                               'atom'         => 'Instanceof',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it' );
        $this->checkAuto();
        
        return false;
    }
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = fullcode.out("LEFT").next().code + " insteadof " + fullcode.out("RIGHT").next().code;

GREMLIN;
    }
}
?>
