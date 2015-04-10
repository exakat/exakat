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

class Ifthen extends TokenAuto {
    static public $operators = array('T_IF', 'T_ELSEIF');
    static public $atom = 'Ifthen';

    public function _check() {
        // @doc if () with only ;
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_SEMICOLON',
                                              'atom'  => 'none')
        );
        
        $this->actions = array('addEdge'     => array(2 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'property'    => array('alternative' => false),
                               'cleanIndex'  => true);
        $this->checkAuto();

        // @doc if () $x++;
        // Make a block from sequence after a if/elseif
        $this->conditions = array(  0 => array('token'     => self::$operators,
                                               'atom'      => 'none'),
                                    1 => array('atom'      => 'Parenthesis'),
                                    2 => array('notAtom'   => 'Sequence',
                                               'atom'      => 'yes'),
                                    3 => array('token'     => array('T_SEMICOLON', 'T_ELSEIF', 'T_ELSE', 'T_IF',
                                                                    'T_ENDIF', 'T_CLOSE_TAG', 'T_INLINE_HTML',
                                                                    'T_CLOSE_CURLY', 'T_ENDFOREACH', 'T_ENDSWITCH', 
                                                                    'T_ENDFOR', 'T_ENDWHILE', 'T_ENDDECLARE')),
        );
        
        $this->actions = array( 'toBlockIfelseif' => 2,
                                'keepIndexed'     => true);
        $this->checkAuto();

        // @doc if { sequence } then else
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_OPEN_CURLY'),
                                   3 => array('atom'  =>  'Sequence'),
                                   4 => array('token'  => 'T_CLOSE_CURLY'),
                                   5 => array('token' => 'T_ELSE',
                                              'atom'  => 'none'),
                                   6 => array('atom'  => 'Sequence',
                                              'property' => array('block' => true))
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'DROP',
                                                       3 => 'THEN',
                                                       4 => 'DROP',
                                                       5 => 'DROP',
                                                       6 => 'ELSE'),
                               'atom'         => 'Ifthen',
                               'property'     => array('alternative' => false),
                               'makeSequence' => 'it',
                               'cleanIndex'   => true);
        $this->checkAuto();

        // @doc if { empty } then else
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('atom'  =>  'Sequence'),
                                   3 => array('token' => 'T_ELSE',
                                              'atom'  => 'none'),
                                   4 => array('atom'  => 'Sequence',
                                              'property' => array('block' => true)),
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'THEN',
                                                       3 => 'DROP',
                                                       4 => 'ELSE'),
                               'atom'         => 'Ifthen',
                               'property'     => array('alternative' => false),
                               'makeSequence' => 'it',
                               'cleanIndex'   => true);
        $this->checkAuto();
        
        // if, elseif followed by a single instruction without a ;
        $this->conditions = array(  0 => array('token' => self::$operators,
                                               'atom'  => 'none'),
                                    1 => array('atom'  => 'Parenthesis'),
                                    2 => array('atom'  => array('For', 'Switch', 'Foreach', 'While', 'Dowhile', 'Ifthen', 'Assignation', 'Return', 'Break' )),
                                    3 => array('filterOut' => array_merge( Staticproperty::$operators, Property::$operators,
                                                                           Logical::$operators))
                                    );
        
        $this->actions = array( 'to_block_ifelseif_instruction' => true,
                                'property'                      => array('alternative' => false),
                                'keepIndexed'                   => true);
        $this->checkAuto();

        // @doc if then without else
        $this->conditions = array( 0 => array('token'   => self::$operators,
                                              'atom'    => 'none'),
                                   1 => array('atom'    => 'Parenthesis'),
                                   2 => array('atom'    => 'Sequence',
                                              'property' => array('block' => true)),
                                   3 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'THEN',
                                                       ),
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => false),
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true);
        $this->checkAuto();

        // @doc if then { block } without else
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_OPEN_CURLY'),
                                   3 => array('atom'  => 'Sequence'),
                                   4 => array('token' => 'T_CLOSE_CURLY'),
                                   5 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'DROP',
                                                       3 => 'THEN',
                                                       4 => 'DROP'),
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => false),
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true);
        $this->checkAuto();

        // @doc if then { block } without else (but within a else alternatif)
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_OPEN_CURLY'),
                                   3 => array('atom'  => 'Sequence'),
                                   4 => array('token' => 'T_CLOSE_CURLY'),
                                   5 => array('token' => array('T_ELSE', 'T_ELSEIF')),
                                   6 => array('token' => 'T_COLON'),
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'DROP',
                                                       3 => 'THEN',
                                                       4 => 'DROP'),
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => false),
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true);
        $this->checkAuto();

        // @doc if then else: (THen, else belongs to another nested ifthen)
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Sequence',
                                              'property' => array('block' => true)),
                                   3 => array('token' => 'T_ELSE'),
                                   4 => array('token' => 'T_COLON'),
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'THEN'),
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => false),
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true);
        $this->checkAuto();
        
        // @doc if then elseif (without else)
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_OPEN_CURLY'),
                                   3 => array('atom'  => 'Sequence'),
                                   4 => array('token' => 'T_CLOSE_CURLY'),
                                   5 => array('atom'  => 'Ifthen',
                                              'token' => 'T_ELSEIF',
                                              'property' => array('alternative' => false))
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'DROP',
                                                       3 => 'THEN',
                                                       4 => 'DROP',
                                                       5 => 'ELSE'
                                                      ),
                               'property'     => array('alternative' => false),
                               'makeSequence' => 'it',
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true
                               );
        $this->checkAuto();

        // @doc if then elseif (without else)
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('atom'  => 'Sequence'),
                                   3 => array('atom'  => 'Ifthen',
                                              'token' => 'T_ELSEIF',
                                              'property' => array('alternative' => false))
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'THEN',
                                                       3 => 'ELSE'
                                                      ),
                               'property'     => array('alternative' => false),
                               'makeSequence' => 'it',
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true
                               );
        $this->checkAuto();
        
    ////////////////////////////////////////////////////////////
    //// Alternative syntax                                 ////
    ////////////////////////////////////////////////////////////
                
    // @doc if () : endif (empty )
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   3 => array('token' => array('T_ENDIF', 'T_ELSEIF', 'T_ELSE')),
        );
        
        $this->actions = array('insertVoid'  => 2,
                               'keepIndexed' => true,
                               'property'    => array('alternative' => true),
                               'cleanIndex'  => true);
        $this->checkAuto();

        // Make a block from sequence after a if/elseif (alternative syntax)
        $this->conditions = array(  0 => array('token'     => self::$operators,
                                               'atom'      => 'none'),
                                    1 => array('atom'      => 'Parenthesis'),
                                    2 => array('token'     => array('T_COLON', 'T_SEMICOLON')),
                                    3 => array('notAtom'   => 'Sequence',
                                               'atom'      => 'yes'),
                                    4 => array('token'     => 'T_SEMICOLON',
                                               'atom'      => 'none'),
                                    5 => array('token'     => array('T_ELSEIF', 'T_ENDIF', 'T_ELSE'))
        );
        
        $this->actions = array( 'toBlockIfelseifAlternative' => 3,
                                'property'        => array('alternative' => true),
                                'keepIndexed'     => true);
        $this->checkAuto();

    // @doc if ( ) : endif
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('token' => 'T_ENDIF'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',
                                                        3 => 'THEN',
                                                        4 => 'DROP'),
                               'property'     => array('alternative' => true),
                               'atom'         => 'Ifthen',
                               'makeSequence' => 'it',
                               'cleanIndex'   => true
                               );
        $this->checkAuto();

    // @doc if ( ) : else: endif (alternative syntax)
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('token' => 'T_ELSE'),
                                   5 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   6 => array('atom'  => 'yes'),
                                   7 => array('token' => array('T_ENDIF', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',
                                                        3 => 'THEN',
                                                        4 => 'DROP',
                                                        5 => 'DROP',
                                                        6 => 'ELSE',
                                                        7 => 'DROP',
                                                      ),
                               'atom'         => 'Ifthen',
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => true),
                               'cleanIndex'   => true
                               );

        $this->checkAuto();

    // @doc if ( ) : elseif
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('atom'  => 'Ifthen',
                                              'token' => 'T_ELSEIF',
                                              'property' => array('alternative' => true) ),
                                   5 => array('filterOut2' => 'T_ENDIF'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',
                                                        3 => 'THEN',
                                                        4 => 'ELSE',
                                                      ),
                               'atom'         => 'Ifthen',
                               'makeSequence' => 'it',
                               'cleanIndex'   => true,
                               'property'     => array('alternative' => true)
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.alternative == true) {
    fullcode.fullcode = fullcode.code + " " + fullcode.out("CONDITION").next().fullcode + " : " + fullcode.out("THEN").next().fullcode + ' endif';
} else {
    fullcode.fullcode = fullcode.code + " " + fullcode.out("CONDITION").next().fullcode + " " + fullcode.out("THEN").next().fullcode;
}

GREMLIN;
    }

}

?>
