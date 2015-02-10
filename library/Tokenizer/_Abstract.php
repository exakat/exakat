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

class _Abstract extends TokenAuto {
    static public $operators = array('T_ABSTRACT');
    static public $atom = 'Abstract';

    public function _check() {
    // abstract class x { abstract function x() }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('token' => array('T_CLASS', 'T_FUNCTION')),
                                 );
        $this->actions = array('to_option' => 1,
                               'atom'   => 'Abstract');
        $this->checkAuto();

    // abstract class x { abstract public function x() }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   2 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 2,
                               'atom'   => 'Abstract');
        $this->checkAuto();

    // abstract class x { abstract public static function x() }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   2 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   3 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 3,
                               'atom'   => 'Abstract');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
if (fullcode.out('ABSTRACT').count() == 1) { fullcode.fullcode = 'abstract ' + fullcode.fullcode; }
GREMLIN;
    }

}
?>
