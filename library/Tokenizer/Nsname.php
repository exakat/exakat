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

class Nsname extends TokenAuto {
    static public $operators = array('T_NS_SEPARATOR');
    static public $atom = 'Nsname';

    public function _check() {
        // @note \a\b\c (\ initial)
        $this->conditions = array( -2 => array('filterOut'  => self::$operators),
                                   -1 => array('filterOut2' => 'T_NS_SEPARATOR'),
                                    0 => array('token'      => self::$operators,
                                               'atom'       => 'none'),
                                    1 => array('atom'       => array('Identifier', 'Boolean', 'Null')),
        );

        $this->actions = array('makeNamespace' => true,
                               'atom'          => 'Nsname',
                               'keepIndexed'   => true,
                               'makeSequence'  => 'it'
                               );
        $this->checkAuto();

        // @note a\b\c as F
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => _As::$operators),
                                   2 => array('atom'  => 'Identifier'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'DROP',
                                                         2 => 'AS' ),
                               'atom'          => 'As',
                               'cleanIndex'    => true,
                               'makeSequence'  => 'it'
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out("SUBNAME").sort{it.rank}._().each{ s.add(it.fullcode); };

if (fullcode.absolutens == true) {
    fullcode.setProperty('fullcode', "\\\\");
} else {
    fullcode.setProperty('fullcode', "");
}

if (s.size() == 0) { // no ELEMENT : simple NS
    fullcode.setProperty('fullcode', fullcode.getProperty('fullcode') + fullcode.getProperty('code'));
} else {
    fullcode.setProperty('fullcode', fullcode.getProperty('fullcode') + s.join("\\\\"));
}

fullcode.out('AS').each{
    fullcode.setProperty('fullcode', fullcode.getProperty('fullcode') + ' as ' + it.code);
}

GREMLIN;
    }
}
?>
