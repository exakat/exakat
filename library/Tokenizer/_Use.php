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

class _Use extends TokenAuto {
    static public $operators = array('T_USE');
    static public $atom = 'Use';

    public function _check() {
    // use \a\b;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => array('Nsname', 'Identifier', 'As')),
                                   2 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG')));
        
        $this->actions = array('transform'    => array( 1 => 'USE'),
                               'atom'         => 'Use',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

    // use \b\c, \a\c;
        $this->conditions = array( 0 => array('token'    => _Use::$operators),
                                   1 => array('atom'     => 'Arguments'),
                                   2 => array('token'    => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'     => 'none')
                                 );
        
        $this->actions = array('toUse'        => true,
                               'atom'         => 'Use',
                               'makeSequence' => 'it' );
        $this->checkAuto();

    // use const \a\b;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('token' => array('T_CONST', 'T_FUNCTION')),
                                   2 => array('atom'  => array('Nsname', 'Identifier', 'As')),
                                   3 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'  => 'none')
                                 );
        
        $this->actions = array('toUseConst'   => true,
                               'atom'         => 'Use',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

    // use const \b\c, \a\c;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('token' => array('T_CONST', 'T_FUNCTION')),
                                   2 => array('atom'  => 'Arguments'),
                                   3 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'  => 'none')
                                 );
        
        $this->actions = array('toUse'        => true,
                               'atom'         => 'Use',
                               'makeSequence' => 'it' );
        $this->checkAuto();

    // use A { B as C; }
        $this->conditions = array( 0 => array('token'    => _Use::$operators),
                                   1 => array('atom'     => array('Nsname', 'Identifier')),
                                   2 => array('token'    => 'T_OPEN_CURLY',
                                              'property' => array('association' => 'Use')),
                                   3 => array('atom'     => array('Sequence', 'Void')),
                                   4 => array('token'    => 'T_CLOSE_CURLY'),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'USE',
                                                      2 => 'DROP',
                                                      3 => 'BLOCK',
                                                      4 => 'DROP',
                                                      ),
                               'atom'       => 'Use',
                               'cleanIndex' => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

    // use A,B {};
        $this->conditions = array( 0 => array('token'    => _Use::$operators),
                                   1 => array('atom'     => 'Arguments'),
                                   2 => array('token'    => 'T_OPEN_CURLY',
                                              'property' => array('association' => 'Use')),
                                   3 => array('atom'     => array('Sequence', 'Void')),
                                   4 => array('token'    => 'T_CLOSE_CURLY'),
                                 );
        
        $this->actions = array('toUseBlock' => true,
                               'atom'         => 'Use',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'  );
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out('USE', 'FUNCTION', 'CONST').sort{it.rank}._().each{
    a = it.getProperty('fullcode');
    s.add(a);
};
if (fullcode.out('FUNCTION').any()) {
    fullcode.setProperty('fullcode', fullcode.getProperty('code') + " function " + s.join(", "));
} else if (it.out('CONST').any()) {
    fullcode.setProperty('fullcode', fullcode.getProperty('code') + " const " + s.join(", "));
} else {
    fullcode.setProperty('fullcode', fullcode.getProperty('code') + " " + s.join(", "));
}

// use a (aka c);
fullcode.out('USE').has('atom', 'Identifier').each{
    it.setProperty('originpath', it.code.toLowerCase());
    it.setProperty('originclass', it.code);
    
    it.setProperty('alias', it.code.toLowerCase());
    it.setProperty('originalias', it.code);
}

// use a\b\c (aka c);
fullcode.out('USE').has('atom', 'As').each{
    s = [];
    it.out("SUBNAME").sort{it.rank}._().each{
        s.add(it.getProperty('code'));
    };
    if (it.absolutens == true) {
        it.setProperty('originpath', '\\\\' + s.join('\\\\').toLowerCase());
        it.setProperty('originclass', s.pop());
    } else {
        it.setProperty('originpath', s.join('\\\\').toLowerCase());
        it.setProperty('originclass', s.pop());
    }
    
    it.setProperty('alias', it.out('AS').next().code.toLowerCase());
    it.setProperty('originalias', it.out('AS').next().code);
}

// use a; (aka a)
fullcode.out('USE').has('atom', 'Nsname').each{
    s = [];
    it.out("SUBNAME").sort{it.rank}._().each{
        s.add(it.getProperty('code'));
    };
    if (it.absolutens == true) {
        it.setProperty('originpath', '\\\\' + s.join('\\\\').toLowerCase());
        it.setProperty('originclass', s[s.size() - 1]);
    } else {
        it.setProperty('originpath', s.join('\\\\').toLowerCase());
        it.setProperty('originclass', s[s.size() - 1]);
    }
    
    if (it.out('AS').any()) {
        it.setProperty('alias', it.out('AS').next().code.toLowerCase());
        it.setProperty('originalias', it.out('AS').next().code.toLowerCase());
    } else {
        it.setProperty('alias', s[s.size() - 1].toLowerCase());
        it.setProperty('originalias', s[s.size() - 1]);
    }
}

// use function a as b;
// use const a as b;
fullcode.out('FUNCTION', 'CONST').each{
    s = [];
    it.out("SUBNAME").sort{it.rank}._().each{
        s.add(it.getProperty('code'));
    };
    if (it.absolutens == true) {
        it.setProperty('originpath', '\\\\' + s.join('\\\\').toLowerCase());
        it.setProperty('originclass', s[s.size() - 1]);
    } else {
        it.setProperty('originpath', s.join('\\\\').toLowerCase());
        it.setProperty('originclass', s[s.size() - 1]);
    }
    
    if (it.out('AS').any()) {
        it.setProperty('alias', it.out('AS').next().code.toLowerCase());
        it.setProperty('originalias', it.out('AS').next().code);
    } else {
        it.setProperty('alias', s[s.size() - 1].toLowerCase());
        it.setProperty('originalias', s[s.size() - 1]);
    }
}

GREMLIN;
    }

}
?>
