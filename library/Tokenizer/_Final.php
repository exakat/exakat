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

class _Final extends TokenAuto {
    static public $operators = array('T_FINAL');
    static public $atom = 'Final';

    public function _check() {
    // final class x { final function x() }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('token' => array('T_CLASS', 'T_FUNCTION')),
                                 );
        $this->actions = array('toOption' => 1,
                               'atom'     => 'Final');
        $this->checkAuto();

    // final class x { final private function x() }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   2 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('toOption' => 2,
                               'atom'     => 'Final');
        $this->checkAuto();

    // final class x { final private static function x() }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   2 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   3 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('toOption' => 3,
                               'atom'     => 'Final');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        $function = new _Function(Token::$client);
        return $function->fullcode();
    }
}
?>
