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

class _Trait extends TokenAuto {
    static public $operators = array('T_TRAIT');
    static public $atom = 'Trait';
    
    protected $phpVersion = '5.4+';

    public function _check() {
        // trait t { normal code }
        $this->conditions = array(0 => array('token' => _Trait::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Boolean', 'Null')),
                                  2 => array('token' => 'T_OPEN_CURLY',
                                             'property' => array('association' => 'Trait')),
                                  3 => array('atom'  => array('Sequence','Void')),
                                  4 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAME',
                                                        2 => 'DROP',
                                                        3 => 'BLOCK',
                                                        4 => 'DROP',
                                                        ),
                               'atom'         => 'Trait',
                               'makeBlock'    => 'BLOCK',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "trait " + fullcode.out("NAME").next().code;

GREMLIN;
    }

}

?>
