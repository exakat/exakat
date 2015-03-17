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

class _Finally extends TokenAuto {
    static public $operators = array('T_FINALLY');
    static public $atom = 'Finally';

    public function _check() {
        $this->conditions = array(0 => array('token' => _Finally::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => 'Sequence',
                                             'property' => array('block' => true)),
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'CODE' ),
                               'cleanIndex' => true,
                               'atom'       => 'Finally');
                               
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
fullcode.setProperty('fullcode', "finally " + fullcode.out("CODE").next().getProperty('fullcode'));
GREMLIN;
    }
}

?>
