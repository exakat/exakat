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

class _Foreach extends TokenAuto {
    static public $operators = array('T_FOREACH');
    static public $atom = 'Foreach';

    public function _check() {
        $operands = array('Variable', 'Array', 'Property', 'Staticproperty', 'Functioncall',
                          'Staticmethodcall', 'Methodcall','Cast', 'Parenthesis', 'Ternary',
                          'Noscream', 'Not', 'Assignation', 'New', 'Addition', 'Clone', 'Include');
        $blindVariables = array('Variable', 'Keyvalue', 'Array', 'Staticproperty', 'Property', 'Functioncall' );

        // foreach(); (empty)
        $this->conditions = array( 0 => array('token' => _Foreach::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'  => $operands),
                                   3 => array('token' => 'T_AS'),
                                   4 => array('atom'  => $blindVariables),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('token' => 'T_SEMICOLON')
        );
        $this->actions = array('addEdge'      => array(6 => array('Void' => 'LEVEL')),
                               'keepIndexed'  => true,
                               'cleanIndex'   => true);
        $this->checkAuto();

        // foreach() $x++; (one instruction)
        $this->conditions = array( 0 => array('token'   => _Foreach::$operators,
                                              'atom'    => 'none'),
                                   1 => array('token'   => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'    => $operands),
                                   3 => array('token'   => 'T_AS'),
                                   4 => array('atom'    => $blindVariables),
                                   5 => array('token'   => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('atom'    => 'yes',
                                              'notAtom' => 'Sequence'),
                                   7 => array('filterOut' => array_merge(array('T_OPEN_BRACKET', 'T_OBJECT_OPERATOR',
                                                                               'T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS'),
                                                                Assignation::$operators, Addition::$operators,
                                                                Multiplication::$operators)),
        );
        $this->actions = array( 'toBlockForeach'   => 6,
                                'keepIndexed'      => true,
                                'cleanIndex'       => true);
        $this->checkAuto();

    // @doc foreach($x as $y) { code }
        $this->conditions = array( 0 => array('token' => _Foreach::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'  => $operands),
                                   3 => array('token' => 'T_AS'),
                                   4 => array('atom'  => $blindVariables),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('token' => 'T_OPEN_CURLY'),
                                   7 => array('atom'  => array('Sequence', 'Void')),
                                   8 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'SOURCE',
                                                       3 => 'DROP',
                                                       4 => 'VALUE',
                                                       5 => 'DROP',
                                                       6 => 'DROP',
                                                       7 => 'BLOCK',
                                                       8 => 'DROP',
                                                      ),
                               'atom'         => 'Foreach',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

    // @doc foreach($a as $b) : code endforeach
        $this->conditions = array( 0  => array('token' => _Foreach::$operators,
                                               'atom' => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'  => $operands),
                                   3 => array('token' => 'T_AS'),
                                   4 => array('atom'  => $blindVariables),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('token' => 'T_COLON',
                                              'property' => array('association' => 'Foreach')),
                                   7 => array('atom'  => 'yes'),
                                   8 => array('atom'  => 'yes'),
                                   9 => array('token' => 'T_ENDFOREACH'),
        );
        
        $this->actions = array( 'makeForeachSequence' => true,
                                'keepIndexed' => true);
        $this->checkAuto();

    // @doc foreach($a as $b) : code endforeach
        $this->conditions = array( 0  => array('token' => _Foreach::$operators,
                                               'atom' => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'  => $operands),
                                   3 => array('token' => 'T_AS'),
                                   4 => array('atom'  => $blindVariables),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('token' => 'T_COLON',
                                              'property' => array('association' => 'Foreach')),
                                   7 => array('atom'  => 'yes'),
                                   8 => array('token' => 'T_ENDFOREACH'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'SOURCE',
                                                        3 => 'DROP',
                                                        4 => 'VALUE',
                                                        5 => 'DROP',
                                                        6 => 'DROP',
                                                        7 => 'BLOCK',
                                                        8 => 'DROP',
                                                      ),
                               'atom'         => 'Foreach',
                               'property'     => array('alternative' => true),
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

    // @doc foreach($a as $b) : code ; endforeach
        $this->conditions = array( 0 => array('token'  => _Foreach::$operators,
                                              'atom'   => 'none'),
                                   1 => array('token'  => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'Foreach')),
                                   2 => array('atom'   => $operands),
                                   3 => array('token'  => 'T_AS'),
                                   4 => array('atom'   => $blindVariables),
                                   5 => array('token'  => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('token'  => 'T_COLON',
                                              'property' => array('association' => 'Foreach')),
                                   7 => array('atom'   => 'yes'),
                                   8 => array('token'  => 'T_SEMICOLON',
                                              'atom'   => 'none'),
                                   9 => array('token'  => 'T_ENDFOREACH'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'SOURCE',
                                                        3 => 'DROP',
                                                        4 => 'VALUE',
                                                        5 => 'DROP',
                                                        6 => 'DROP',
                                                        7 => 'BLOCK',
                                                        8 => 'DROP',
                                                        9 => 'DROP',
                                                      ),
                               'atom'         => 'Foreach',
                               'property'     => array('alternative' => true),
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
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
