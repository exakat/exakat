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

class _For extends TokenAuto {
    static public $operators = array('T_FOR');
    static public $atom = 'For';

    public function _check() {
        // for (;;) ; (Empty loop)
        $this->conditions = array(  0 => array('token' => _For::$operators,
                                               'atom'  => 'none'),
                                    1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                    2 => array('atom'  => 'yes'),
                                    3 => array('token' => 'T_SEMICOLON'),
                                    4 => array('atom'  => 'yes'),
                                    5 => array('token' => 'T_SEMICOLON'),
                                    6 => array('atom'  => 'yes'),
                                    7 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    8 => array('token' => 'T_SEMICOLON', 
                                               'atom'  => 'none'),
        );
        $this->actions = array('addEdge'     => array(8 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto();
        
        // for (;;) $x++; (one line instruction, with or without )
        $this->conditions = array(  0 => array('token'     => _For::$operators,
                                               'atom'      => 'none'),
                                    1 => array('token'     => 'T_OPEN_PARENTHESIS'),
                                    2 => array('atom'      => 'yes'),
                                    3 => array('token'     => 'T_SEMICOLON'),
                                    4 => array('atom'      => 'yes'),
                                    5 => array('token'     => 'T_SEMICOLON'),
                                    6 => array('atom'      => 'yes'),
                                    7 => array('token'     => 'T_CLOSE_PARENTHESIS'),
                                    8 => array('atom'      => 'yes',
                                               'notAtom'   => 'Sequence'),
                                    9 => array('filterOut' => Token::$instructionEnding),
        );
        $this->actions = array('to_block_for' => true,
                               'keepIndexed'  => true,
                               'cleanIndex'   => true);
        $this->checkAuto();
    
    // @doc for(a; b; c) { code }
        $this->conditions = array(  0 => array('token' => _For::$operators,
                                               'atom'  => 'none'),
                                    1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                    2 => array('atom'  => 'yes'),
                                    3 => array('token' => 'T_SEMICOLON'),
                                    4 => array('atom'  => 'yes'),
                                    5 => array('token' => 'T_SEMICOLON'),
                                    6 => array('atom'  => 'yes'),
                                    7 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    8 => array('token' => 'T_OPEN_CURLY'),
                                    9 => array('atom'  => array('Sequence', 'Void')),
                                   10 => array('token' => 'T_CLOSE_CURLY')
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'INIT',
                                                        3 => 'DROP',
                                                        4 => 'FINAL',
                                                        5 => 'DROP',
                                                        6 => 'INCREMENT',
                                                        7 => 'DROP',
                                                        8 => 'DROP',
                                                        9 => 'BLOCK',
                                                       10 => 'DROP'
                                                      ),
                               'atom'         => 'For',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

    // @doc for(a; b; c) { code }
        $this->conditions = array(  0 => array('token' => _For::$operators,
                                               'atom'  => 'none'),
                                    1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                    2 => array('atom'  => 'yes'),
                                    3 => array('token' => 'T_SEMICOLON'),
                                    4 => array('atom'  => 'yes'),
                                    5 => array('token' => 'T_SEMICOLON'),
                                    6 => array('atom'  => 'yes'),
                                    7 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    8 => array('atom'  => 'Sequence')
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'INIT',
                                                        3 => 'DROP',
                                                        4 => 'FINAL',
                                                        5 => 'DROP',
                                                        6 => 'INCREMENT',
                                                        7 => 'DROP',
                                                        8 => 'BLOCK'
                                                      ),
                               'atom'         => 'For',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

    // @doc for(a; b; c) : code endfor
        $this->conditions = array(  0 => array('token' => _For::$operators,
                                               'atom' => 'none'),
                                    1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                    2 => array('atom' => 'yes'),
                                    3 => array('token' => 'T_SEMICOLON'),
                                    4 => array('atom' => 'yes'),
                                    5 => array('token' => 'T_SEMICOLON'),
                                    6 => array('atom' => 'yes'),
                                    7 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    8 => array('token' => 'T_COLON',
                                               'property' => array('relatedAtom' => 'For')),
                                    9 => array('atom' => 'yes'),
                                   10 => array('token' => 'T_ENDFOR'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'INIT',
                                                        3 => 'DROP',
                                                        4 => 'FINAL',
                                                        5 => 'DROP',
                                                        6 => 'INCREMENT',
                                                        7 => 'DROP',
                                                        8 => 'DROP',
                                                        9 => 'BLOCK',
                                                       10 => 'DROP',
                                                      ),
                               'atom'         => 'For',
                               'property'     => array('alternative' => true),
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

    // @doc for(a; b; c) : code ; endfor
        $this->conditions = array( 0  => array('token'  => _For::$operators,
                                               'atom'   => 'none'),
                                   1   => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2   => array('atom'  => 'yes'),
                                   3   => array('token' => 'T_SEMICOLON'),
                                   4   => array('atom'  => 'yes'),
                                   5   => array('token' => 'T_SEMICOLON'),
                                   6   => array('atom'  => 'yes'),
                                   7   => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   8   => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'For')),
                                   9   => array('atom'  => 'yes'),
                                   10  => array('token' => 'T_SEMICOLON',
                                                'atom'  => 'none'),
                                   11  => array('token' => 'T_ENDFOR'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'INIT',
                                                        3 => 'DROP',
                                                        4 => 'FINAL',
                                                        5 => 'DROP',
                                                        6 => 'INCREMENT',
                                                        7 => 'DROP',
                                                        8 => 'DROP',
                                                        9 => 'BLOCK',
                                                       10 => 'DROP',
                                                       11 => 'DROP',
                                                      ),
                               'atom'         => 'For',
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
    it.fullcode = "for(" + it.out("INIT").next().fullcode + " ; " + it.out("FINAL").next().fullcode + " ; " + it.out("INCREMENT").next().fullcode + ") : " + it.out("BLOCK").next().fullcode + ' endfor';
} else {
    it.fullcode = "for(" + it.out("INIT").next().fullcode + " ; " + it.out("FINAL").next().fullcode + " ; " + it.out("INCREMENT").next().fullcode + ") " + it.out("BLOCK").next().fullcode;
}
GREMLIN;
    }
}

?>
