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

class _Default extends TokenAuto {
    static public $operators = array('T_DEFAULT');
    static public $atom = 'Default';

    public function _check() {
        $finalToken = array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT', 'T_ENDSWITCH');
        
     // default : with nothing
        $this->conditions = array(0 => array('token' => _Default::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  2 => array('token' => $finalToken),
        );
        
        $this->actions = array('createVoidForDefault' => true,
                               'keepIndexed'          => true);
        $this->checkAuto();

        // default : ; // rest of the code
        $this->conditions = array(0 => array('token' => _Default::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  2 => array('token' => 'T_SEMICOLON',
                                             'atom'  => 'none')
        );
        
        $this->actions = array('insertVoid' => 1,
                               'keepIndexed'=> true);
        $this->checkAuto();

        // Case has only one instruction (case 'a': $x++;)
        $this->conditions = array( 0 => array('token'   => _Default::$operators,
                                              'atom'    => 'none'),
                                   1 => array('token'   => array('T_COLON', 'T_SEMICOLON')),
                                   2 => array('atom'    => 'yes'),
                                   3 => array('token'   => 'T_SEMICOLON', 
                                              'atom'    => 'none'),
                                   4 => array('token'   => $finalToken));
        
        $this->actions = array('createBlockWithSequenceForDefault' => true,
                               'keepIndexed'                       => true);
        $this->checkAuto();
        
        // default has only one instruction no semi-colon (case 'a': $x++;)
        $this->conditions = array( 0 => array('token'   => _Default::$operators,
                                              'atom'    => 'none'),
                                   1 => array('token'   => array('T_COLON', 'T_SEMICOLON')),
                                   2 => array('atom'    => 'yes', 
                                              'notAtom' => 'Sequence'),
                                   3 => array('token'   => $finalToken));
        
        $this->actions = array('createBlockWithSequenceForDefault' => true,
                               'keepIndexed'                       => true);
        $this->checkAuto();

        // create block for Default  default : $x++ (or a sequence).
        $this->conditions = array(  0 => array('token'   => _Default::$operators,
                                               'atom'    => 'none'),
                                    1 => array('token'   => array('T_COLON', 'T_SEMICOLON'),
                                               'atom'    => 'none'),
                                    2 => array('atom'    => 'yes',
                                               'notAtom' => array('Case', 'Default', 'SequenceCaseDefault', 'Sequence')),
                                    3 => array('token'   => $finalToken),
        );
        
        $this->actions = array('createBlockWithSequenceForDefault' => true,
                               'keepIndexed'                       => true);
        $this->checkAuto();

        // Default with block
        $this->conditions = array(0 => array('token' => _Default::$operators,
                                              'atom' => 'none'),
                                  1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  2 => array('atom' => array('Sequence')),
                                  3 => array('token' => $finalToken),
        );
        
        $this->actions = array('transform'                         => array( 1 => 'DROP',
                                                                             2 => 'CODE',),
                                'atom'                             => 'Default',
                                'cleanIndex'                       => true,
                                'caseDefaultSequence'              => true );
        $this->checkAuto();

        // @note instructions after a default, but not separated by ;
        $this->conditions = array( 0 => array('token' => 'T_DEFAULT',
                                              'atom'  => 'none',),
                                   1 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                              'atom'  => 'none', ),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('filterOut2' => array_merge(array('T_ELSE', 'T_ELSEIF', 'T_OPEN_PARENTHESIS'),
                                                                        Assignation::$operators,    Property::$operators,
                                                                        _Array::$operators,         Bitshift::$operators,
                                                                        Comparison::$operators,     Logical::$operators,
                                                                        Staticproperty::$operators, Spaceship::$operators)),
        );
        
        $this->actions = array('createSequenceForDefaultWithoutSemicolon' => true,
                               'keepIndexed'                              => true);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', "default : " + fullcode.out("CODE").next().getProperty('fullcode'));

GREMLIN;
    }

}

?>
