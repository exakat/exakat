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

class _Static extends TokenAuto {
    static public $operators = array('T_STATIC');
    static public $atom = 'Static';

    public function _check() {
        $values = array('T_EQUAL', 'T_COMMA');
        
        $propertyOptions = array_merge(_Ppp::$operators, _Final::$operators, _Abstract::$operators);

    // class x { static private $x, $y }
    // class x { static public $x = 2 }
    // class x { static private $s }
        $this->conditions = array( 0 => array('token'    => self::$operators),
                                   1 => array('token'    => _Ppp::$operators),
                                   2 => array('notToken' => _Function::$operators)
                                 );
        
        $this->actions = array('toOption' => 1,
                               'atom'     => 'Static');
        $this->checkAuto();

    // class x { static $x = 2 }
    // class x { static $x, $y }
    // class x { static $x }
        $allowedAtoms = array('Assignation', 'Variable');
        $this->conditions = array(-1 => array('notToken' => _Ppp::$operators),
                                   0 => array('token'    => self::$operators,
                                              'checkFor' => $allowedAtoms),
                                   1 => array('atom'     => $allowedAtoms),
                                   2 => array('token'    => array('T_SEMICOLON', 'T_COMMA')),
                                 );
        
        $this->actions = array('makePpp' => 'Visibility',
                               'atom'    => 'Visibility',
                               );
        $this->checkAuto();

    // class x { static function f() }
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('toOption' => 1,
                               'atom'     => 'Static');
        $this->checkAuto();

    // class x { static public function x() }
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => $propertyOptions),
                                   2 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('toOption' => 2,
                               'atom'     => 'Static');
        $this->checkAuto();

    // class x { static private final function f() }
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => $propertyOptions),
                                   2 => array('token' => $propertyOptions),
                                   3 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('toOption' => 3,
                               'atom'     => 'Static');
        $this->checkAuto();




    // static :: ....
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_DOUBLE_COLON'),
                                 );

        $this->actions = array('atom'     => 'Static');
        $this->checkAuto();

    // static :: ....
        $this->conditions = array( -1 => array('token' => 'T_INSTANCEOF'),
                                    0 => array('token' => self::$operators),
                                 );
        $this->actions = array('atom'     => 'Static');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

finalcode = fullcode.code;

s=[];
fullcode.out('DEFINE').sort{it.rank}._().each{ s.add(it.fullcode);}
if (s.size() > 0) {
    finalcode = finalcode + ' ' + s.join(', ');
}

fullcode.setProperty('fullcode', finalcode);

fullcode.out('DEFINE').each{
    if (it.atom == 'Variable') {
        it.setProperty('propertyname', it.code.substring(1, it.code.size()).toLowerCase());
    } else if (it.atom == 'Assignation') {
        it.setProperty('propertyname', it.out('LEFT').next().code.substring(1, it.out('LEFT').next().code.size()).toLowerCase());
    }
}

GREMLIN;
    }
}
?>
