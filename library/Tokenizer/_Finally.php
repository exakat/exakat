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
        // finally {}
        $this->conditions = array(0 => array('token'    => _Finally::$operators,
                                             'atom'     => 'none'),
                                  1 => array('token'    => 'T_OPEN_CURLY',
                                             'property' => array('association' => 'Finally')),
                                  2 => array('atom'     => array('Sequence', 'Void')),
                                  3 => array('token'    => 'T_CLOSE_CURLY'),
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'DROP',
                                                      2 => 'CODE',
                                                      3 => 'DROP' ),
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
