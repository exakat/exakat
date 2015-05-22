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

class _Function extends TokenAuto {
    static public $operators = array('T_FUNCTION');
    static public $atom = 'Function';
    
    public function _check() {
        // function x(args) { normal code }
        $this->conditions = array(0 => array('token' => _Function::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Boolean', 'Null')),
                                  2 => array('token' => 'T_OPEN_PARENTHESIS',
//                                             'property' => array('association' => 'Function')
                                             ),
                                  3 => array('atom'  => 'Arguments'),
                                  4 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  5 => array('token' => 'T_OPEN_CURLY',
                                             'atom'  => 'none',
                                             'property' => array('association' => 'Function')
                                             ),
                                  6 => array('atom'  => array('Sequence', 'Void')),
                                  7 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'NAME',
                                                         2 => 'DROP',
                                                         3 => 'ARGUMENTS',
                                                         4 => 'DROP',
                                                         5 => 'DROP',
                                                         6 => 'BLOCK',
                                                         7 => 'DROP'),
                               'atom'          => 'Function',
                               'checkTypehint' => 'Function',
                               'makeBlock'     => 'BLOCK',
                               'cleanIndex'    => true,
                               'addSemicolon'  => 'it');
        $this->checkAuto();

        // function x(args); for interfaces or abstract
        $this->conditions = array(0 => array('token' =>  _Function::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Boolean', 'Null')),
                                  2 => array('token' => 'T_OPEN_PARENTHESIS',
//                                             'property' => array('association' => 'Function')
                                             ),
                                  3 => array('atom'  => 'Arguments'),
                                  4 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  5 => array('token' => 'T_SEMICOLON',
                                             'atom'  => 'none'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'NAME',
                                                         2 => 'DROP',
                                                         3 => 'ARGUMENTS',
                                                         4 => 'DROP',
                                                         5 => 'DROP'),
                               'atom'          => 'Function',
                               'checkTypehint' => 'Function',
                               'addSemicolon'  => 'it',
                               'cleanIndex'    => true);
        $this->checkAuto();

        // lambda function (no name)
        $this->conditions = array(0 => array('token'    =>  _Function::$operators,
                                             'atom'     => 'none'),
                                  1 => array('token'    => 'T_OPEN_PARENTHESIS',
//                                             'property' => array('association' => 'Function')
                                             ),
                                  2 => array('atom'     => 'Arguments'),
                                  3 => array('token'    => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token'    => 'T_OPEN_CURLY',
                                             'property' => array('association' => 'Function')
                                             ),
                                  5 => array('atom'     => array('Sequence', 'Void')),
                                  6 => array('token'    => 'T_CLOSE_CURLY')
        );
        
        $this->actions = array('toLambda'      => true,
                               'atom'          => 'Function',
                               'checkTypehint' => 'Function',
                               'cleanIndex'    => true,
                               'addSemicolon'  => 'it',
                               'makeBlock'      => 'BLOCK');
        $this->checkAuto();

        // lambda function ($x) use ($y)
        $this->conditions = array(0  => array('token' =>  _Function::$operators,
                                              'atom'  => 'none'),
                                  1  => array('token' => 'T_OPEN_PARENTHESIS',
//                                              'property' => array('association' => 'Function')
                                              ),
                                  2  => array('atom'  => 'Arguments'),
                                  3  => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4  => array('token' => 'T_USE'),
                                  5  => array('token' => 'T_OPEN_PARENTHESIS'),
                                  6  => array('atom'  => 'Arguments'),
                                  7  => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  8  => array('token' => 'T_OPEN_CURLY',
                                              'property' => array('association' => 'Use')
                                              ),
                                  9  => array('atom'  => array('Sequence', 'Void')),
                                  10 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('toLambdaUse'    => true,
                               'atom'           => 'Function',
                               'checkTypehint'  => 'Function',
                               'cleanIndex'     => true,
                               'addSemicolon'   => 'it',
                               'makeBlock'      => 'BLOCK');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', '');

// for methods
fullcode.filter{it.out("USE").count() == 0 && it.out("NAME").count() == 1 && it.out("BLOCK").count() == 0}.each{ fullcode.fullcode = "function " + fullcode.out("NAME").next().fullcode + " (" + fullcode.out("ARGUMENTS").next().fullcode + ") ;";}

fullcode.filter{it.out("USE").count() == 0 && it.out("NAME").count() == 1 && it.out("BLOCK").count() == 1}.each{ fullcode.fullcode = "function " + fullcode.out("NAME").next().fullcode + " (" + fullcode.out("ARGUMENTS").next().fullcode + ") " + fullcode.out("BLOCK").next().fullcode;}

fullcode.filter{it.out("USE").count() == 0 && it.out("NAME").count() == 0 && it.out("BLOCK").count() == 1}.each{ fullcode.fullcode = "function (" + fullcode.out("ARGUMENTS").next().fullcode + ") " + fullcode.out("BLOCK").next().fullcode;}

fullcode.filter{it.out("USE").any()}.each{ fullcode.fullcode = "function (" + fullcode.out("ARGUMENTS").next().fullcode + ") use " + fullcode.out("USE").next().fullcode + " " + fullcode.out("BLOCK").next().fullcode;}

// for properties
if (fullcode.out('DEFINE').any()) {
    fullcode.setProperty('fullcode', fullcode.getProperty('fullcode') + fullcode.out('DEFINE').next().getProperty('fullcode'));
    fullcode.setProperty('propertyname', fullcode.out('DEFINE').next().getProperty('fullcode').substring(1, fullcode.out('DEFINE').next().getProperty('fullcode').size()) );
    fullcode.out('PROTECTED', 'PRIVATE', 'PUBLIC').each{ it.setProperty('atom', 'Visibility'); }
} else {
    fullcode.setProperty('args_min', fullcode.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Variable').count());
    if (fullcode.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Variable').has('variadic', true).any()) {
        fullcode.setProperty('args_max', 100);
    } else {
        fullcode.setProperty('args_max', fullcode.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Assignation').count() + fullcode.getProperty('args_min'));
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

GREMLIN;
    }
}

?>
