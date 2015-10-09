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

class _Ppp extends TokenAuto {
    static public $operators = array('T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC');
    static public $atom = 'Visibility';

    public function _check() {
        $values = array('T_EQUAL', 'T_COMMA');

/*
    // class x { static private $s }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => 'T_STATIC'),
                                   2 => array('token' => 'T_VARIABLE'),
                                 );
        $this->actions = array('toOption' => 1,
                               'atom'     => 'Ppp');
        $this->checkAuto();

    // class x { public static $x = 2; }
        $this->conditions = array(-1 => array('notToken' => 'STATIC'),
                                   0 => array('token'    =>  _Ppp::$operators),
                                   1 => array('atom'     => 'Assignation'),
                                   2 => array('token'    => 'T_SEMICOLON'),
                                 );
        
        $this->actions = array('to_ppp_assignation' => true,
                               'atom'               => 'Ppp',
                               'addSemicolon'       => 'x'
                               );
        $this->checkAuto();


    // class x { protected function f()  }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('toOption' => 1,
                               'atom'     => 'Ppp');
        $this->checkAuto();

    // class x { protected private function f()  }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => array('T_ABSTRACT', 'T_FINAL', 'T_STATIC')),
                                   2 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('toOption' => 2,
                               'atom'     => 'Ppp');
        $this->checkAuto();

    // class x { protected private static function f()  }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => array('T_ABSTRACT', 'T_FINAL', 'T_STATIC')),
                                   2 => array('token' => array('T_ABSTRACT', 'T_FINAL', 'T_STATIC')),
                                   3 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('toOption' => 3,
                               'atom'     => 'Ppp');
        $this->checkAuto();
*/
    // class x { public $x }
    // class x { public $x = 2 }
    // class x { public $x, $y }
    // class x { public $x = 2, $y = 2 }
        $allowedAtoms = array('Assignation', 'Variable');
        $this->conditions = array(-1 => array('notToken' => 'STATIC'),
                                   0 => array('token'    => _Ppp::$operators,
                                              'checkFor' => $allowedAtoms),
                                   1 => array('atom'     => $allowedAtoms),
                                   2 => array('token'    => array('T_SEMICOLON', 'T_COMMA')),
                                 );
        
        $this->actions = array('makePpp' => 'Ppp',
                               'atom'    => 'Ppp',
                               );
        $this->checkAuto();

    // class x { private static $x, $y }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => array_merge(_Static::$operators, _Function::$operators))
                                 );
        
        $this->actions = array('toOption' => 1,
                               'atom'     => 'Ppp');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

s=[];
fullcode.out('CONST').each{ s.add(it.fullcode);}
fullcode.setProperty('fullcode', 'const ' + s.join(', '));

GREMLIN;
    }
}
?>
