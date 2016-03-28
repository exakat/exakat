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

class _Function extends TokenAuto {
    static public $operators = array('T_FUNCTION');
    static public $atom = 'Function';
    
    public function _check() {

        // function name ( arguments )
        $this->conditions = array(0 => array('token' => self::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_AND')
        );
        
        $this->actions = array('transform'     => array( 1 => 'DROP'),
                               'property'      => array('reference' => true),
                               'keepIndexed'   => true);
        $this->checkAuto();

        // function name ( arguments )
        $this->conditions = array(0 => array('token'    => self::$operators,
                                             'atom'     => 'none'),
                                  1 => array('notToken' => 'T_OPEN_CURLY'),
// Function name may be anything, indeed. 
                                  2 => array('token'    => 'T_OPEN_PARENTHESIS',
                                             'property' => array('association' => 'Function')
                                             ),
                                  3 => array('atom'     => 'Arguments'),
                                  4 => array('token'    => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('transform'     => array( 1 => 'NAME',
                                                         2 => 'DROP',
                                                         3 => 'ARGUMENTS',
                                                         4 => 'DROP'),
                               'checkTypehint' => 'Function',
                               'cleanIndex'    => true,
                               'keepIndexed'   => true);
        $this->checkAuto();

        // function : returnType
        $this->conditions = array(0 => array('token' => self::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_COLON'),
                                        // check for association?
                                  2 => array('token' => array('T_STRING', 'T_NS_SEPARATOR', 'T_ARRAY', 'T_CALLABLE'))
        );
        
        $this->actions = array('transform'     => array( 1 => 'DROP',
                                                         2 => 'RETURN'),
                               'cleanIndex'    => true,
                               'keepIndexed'   => true);
        $this->checkAuto();

        // Closures (no names)
        $this->conditions = array(0 => array('token'    =>  self::$operators,
                                             'atom'     => 'none'),
                                  1 => array('token'    => 'T_OPEN_PARENTHESIS',
                                             'property' => array('association' => 'Function')
                                             ),
                                  2 => array('atom'     => 'Arguments'),
                                  3 => array('token'    => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('toLambda'      => true,
                               'checkTypehint' => 'Function',
                               'cleanIndex'    => true,
                               'keepIndexed'   => true);
        $this->checkAuto();

        // function use ($y)
        $this->conditions = array(0  => array('token' =>  self::$operators,
                                              'atom'  => 'none'),
                                  1  => array('token' => 'T_USE'),
                                  2  => array('token' => 'T_OPEN_PARENTHESIS'),
                                  3  => array('atom'  => 'Arguments'),
                                  4  => array('token' => 'T_CLOSE_PARENTHESIS'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'DROP',
                                                         2 => 'DROP',
                                                         3 => 'USE',
                                                         4 => 'DROP'),
                               'cleanIndex'    => true,
                               'keepIndexed'   => true);
        $this->checkAuto();

        // function x(args) { normal code }
        $this->conditions = array(0 => array('token' => self::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_CURLY',
                                             'atom'  => 'none',
//                                             'property' => array('association' => 'Function')
                                             ),
                                  2 => array('atom'  => array('Sequence', 'Void')),
                                  3 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'DROP',
                                                         2 => 'BLOCK',
                                                         3 => 'DROP'),
                               'atom'          => 'Function',
                               'makeBlock'     => 'BLOCK',
                               'cleanIndex'    => true,
                               'addSemicolonFunction'  => 'it'
                               );
        $this->checkAuto();

        // function ; (No Body, for interfaces or abstract)
        $this->conditions = array(0 => array('token' => self::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_SEMICOLON'),
        );
        
        $this->actions = array('atom'          => 'Function',
                               'cleanIndex'    => true);
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', '');

// Those won't get a chance to be made into an atom.
fullcode.out('RETURN').filter{ it.token in ['T_CALLABLE', 'T_ARRAY'] }.each{
    it.fullcode = it.code;
    it.atom = 'Identifer';
}

// for properties
if (fullcode.out('DEFINE').any()) {
    fullcode.setProperty('fullcode', fullcode.getProperty('fullcode') + fullcode.out('DEFINE').next().getProperty('fullcode'));
    fullcode.out('PROTECTED', 'PRIVATE', 'PUBLIC').each{ it.setProperty('atom', 'Visibility'); }
} else if (fullcode.token in ['T_STATIC']) {
    // Then, this is an identifier, like self::method();
    fullcode.setProperty('fullcode', fullcode.getProperty('code'));
} else {
    // for methods

    if (fullcode.reference == true) {
        fullcode.fullcode = 'function &';
    } else {
        fullcode.fullcode = 'function ';
    }

    if (fullcode.out('NAME').any() && fullcode.out('NAME').next().fullcode == null) { 
        name = fullcode.out('NAME').next();
        name.fullcode = name.code;
        name.atom = 'String';
    }

    if (fullcode.out('NAME').any())      { fullcode.fullcode = fullcode.fullcode +           fullcode.out('NAME').next().fullcode;            }
    if (fullcode.out('ARGUMENTS').any()) { fullcode.fullcode = fullcode.fullcode + '(' +     fullcode.out('ARGUMENTS').next().fullcode + ')'; }
    if (fullcode.out('USE').any())       { fullcode.fullcode = fullcode.fullcode + ' use ('+ fullcode.out('USE').next().fullcode + ')';       }
    if (fullcode.out('RETURN').any())    { fullcode.fullcode = fullcode.fullcode + ' : ' +   fullcode.out('RETURN').next().fullcode;          }
    if (fullcode.out('BLOCK').any())     { fullcode.fullcode = fullcode.fullcode + ' ' +     fullcode.out('BLOCK').next().fullcode;           }

    args_min = fullcode.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Variable').count();
    args_min += fullcode.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Typehint').out('VARIABLE').has('atom', 'Variable').count();
    fullcode.setProperty('args_min', args_min);
    
    if (fullcode.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Variable').has('variadic', true).any()) {
        fullcode.setProperty('args_max', 100);
    } else {
        fullcode.setProperty('args_max', fullcode.out('ARGUMENTS').out('ARGUMENT').hasNot('token', 'T_VOID').count());
    }
    // No support for T_ELLIPSIS yet :( => 100!
    
}
if (fullcode.out('VALUE').hasNot('atom', 'Void').count() == 1) { fullcode.fullcode = fullcode.fullcode + ' = ' + fullcode.out('VALUE').next().fullcode; }

// optional attributes
if (fullcode.out('ABSTRACT').any())  { fullcode.fullcode = 'abstract ' + fullcode.fullcode; }
if (fullcode.out('FINAL').any())     { fullcode.fullcode = 'final ' + fullcode.fullcode; }
if (fullcode.out('STATIC').any())    { fullcode.fullcode = 'static ' + fullcode.fullcode; }
if (fullcode.out('VAR').any())       { fullcode.fullcode = 'var ' + fullcode.fullcode; }

if (fullcode.out('PUBLIC').any())    { fullcode.fullcode = 'public ' + fullcode.fullcode; }
if (fullcode.out('PROTECTED').any()) { fullcode.fullcode = 'protected ' + fullcode.fullcode; }
if (fullcode.out('PRIVATE').any())   { fullcode.fullcode = 'private ' + fullcode.fullcode; }

// for tokens that are not a class structure definition
fullcode.has('fullcode', '').each{ fullcode.setProperty('fullcode', fullcode.getProperty('code'));}

fullcode.setProperty("args_count", fullcode.out("ARGUMENTS").out("ARGUMENT").hasNot('token', 'T_VOID').count());

GREMLIN;
    }
}

?>
