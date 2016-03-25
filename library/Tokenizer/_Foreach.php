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

class _Foreach extends TokenAuto {
    static public $operators = array('T_FOREACH');
    static public $atom = 'Foreach';

    public function _check() {
        $operands       = array('Variable', 'Array', 'Property', 'Staticproperty', 'Functioncall',
                                'Staticmethodcall', 'Methodcall','Cast', 'Parenthesis', 'Ternary', 'Staticconstant',
                                'Noscream', 'Not', 'Assignation', 'New', 'Addition', 'Clone', 'Include',
                                'Coalesce', 'Ternary', 'Null', 'Boolean', 'Identifier', 'Nsname');
        $blindVariables = array('Variable', 'Keyvalue', 'Array', 'Staticproperty', 'Property', 'Functioncall' );


    // @doc foreach($x as $y) (No code yet)
        $this->conditions = array( 0 => array('token'    => _Foreach::$operators,
                                              'atom'     => 'none'),
                                   1 => array('token'    => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'     => $operands),
                                   3 => array('token'    => 'T_AS'),
                                   4 => array('atom'     => $blindVariables),
                                   5 => array('token'    => 'T_CLOSE_PARENTHESIS'),
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'SOURCE',
                                                       3 => 'DROP',
                                                       4 => 'VALUE',
                                                       5 => 'DROP'
                                                      ),
                               'keepIndexed'   => true,
                               'cleanIndex'    => true
                              );
        $this->checkAuto();

        // foreach(); (empty)
        $this->conditions = array( 0 => array('token' => _Foreach::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_SEMICOLON',
                                               'atom' => 'none')
        );
        $this->actions = array('addEdge'      => array(1 => array('Void' => 'LEVEL')),
                               'keepIndexed'  => true,
                               'cleanIndex'   => true);
        $this->checkAuto();

        // foreach($x as $y) $x++; (one instruction)
        $this->conditions = array( 0 => array('token'     => _Foreach::$operators,
                                              'atom'      => 'none'),
                                   1 => array('atom'      => 'yes',
                                              'notAtom'   => 'Sequence'),
                                   2 => array('filterOut' => Token::$instructionEnding),
        );
        $this->actions = array( 'toBlockForeach' => 1,
                                'keepIndexed'    => true,
                                'cleanIndex'     => true);
        $this->checkAuto();

    // @doc foreach($x as $y) { code }
        $this->conditions = array( 0 => array('token'    => _Foreach::$operators,
                                              'atom'     => 'none'),
                                   1 => array('token'    => 'T_OPEN_CURLY'),
                                   2 => array('atom'     => array('Sequence', 'Void')),
                                   3 => array('token'    => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'BLOCK',
                                                       3 => 'DROP',
                                                      ),
                               'atom'               => 'Foreach',
                               'cleanIndex'         => true,
                               'addAlwaysSemicolon' => 'it',
                               'makeBlock'          => 'BLOCK');
        $this->checkAuto();

    // @doc foreach($a as $b) : code endforeach
        $this->conditions = array( 0  => array('token'   => _Foreach::$operators,
                                               'atom'    => 'none'),
                                   1 => array('token'    => 'T_COLON',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'     => 'yes'),
                                   3 => array('atom'     => 'yes'),
                                   4 => array('token'    => 'T_ENDFOREACH'),
        );
        
        $this->actions = array( 'makeForeachSequence' => true,
                                'keepIndexed'         => true);
        $this->checkAuto();

    // @doc foreach($a as $b) : code endforeach
        $this->conditions = array( 0  => array('token'   => _Foreach::$operators,
                                               'atom'    => 'none'),
                                   1 => array('token'    => 'T_COLON',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'     => 'yes'),
                                   3 => array('token'    => 'T_ENDFOREACH'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'BLOCK',
                                                        3 => 'DROP',
                                                      ),
                               'atom'         => 'Foreach',
                               'property'     => array('alternative' => true),
                               'cleanIndex'   => true,
                               'addSemicolon' => 'it'
                               );
        $this->checkAuto();

    // @doc foreach($a as $b) : code ; endforeach
        $this->conditions = array( 0 => array('token'    => _Foreach::$operators,
                                              'atom'     => 'none'),
                                   1 => array('token'    => 'T_COLON',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'     => 'yes'),
                                   3 => array('token'    => 'T_SEMICOLON',
                                              'atom'     => 'none'),
                                   4 => array('token'    => 'T_ENDFOREACH'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'BLOCK',
                                                        3 => 'DROP',
                                                        4 => 'DROP',
                                                      ),
                               'atom'         => 'Foreach',
                               'property'     => array('alternative' => true),
                               'cleanIndex'   => true
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (it.alternative == true) {
    fullcode.setProperty("fullcode", "foreach(" + it.out("SOURCE").next().getProperty('fullcode') + " as " + it.out("VALUE").next().getProperty('fullcode') + ") : " + it.out("BLOCK").next().getProperty('fullcode') + " endforeach");
} else {
    fullcode.setProperty("fullcode", "foreach(" + it.out("SOURCE").next().getProperty('fullcode') + " as " + it.out("VALUE").next().getProperty('fullcode') + ") " + it.out("BLOCK").next().getProperty('fullcode'));
}

GREMLIN;
    }
}

?>
