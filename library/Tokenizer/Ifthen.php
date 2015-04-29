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
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'If')),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_SEMICOLON',
                                              'atom'  => 'none')
        );
        
        $this->actions = array('addEdge'     => array(4 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'property'    => array('alternative' => false),
                               'cleanIndex'  => true);
        $this->checkAuto();

        // @doc if () $x++;
        // Make a block from sequence after a if/elseif
        $this->conditions = array(  0 => array('token'   => self::$operators,
                                               'atom'    => 'none'),
                                    1 => array('token' => 'T_OPEN_PARENTHESIS',
                                               'property' => array('association' => 'If')),
                                    2 => array('atom'  => 'yes'),
                                    3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    4 => array('notAtom' => 'Sequence',
                                               'atom'    => 'yes'),
                                    5 => array('token'   => array('T_SEMICOLON', 'T_ELSEIF', 'T_ELSE', 'T_IF',
                                                                  'T_ENDIF', 'T_CLOSE_TAG', 'T_INLINE_HTML',
                                                                  'T_CLOSE_CURLY', 'T_ENDFOREACH', 'T_ENDSWITCH',
                                                                  'T_ENDFOR', 'T_ENDWHILE', 'T_ENDDECLARE')),
        );
        
        $this->actions = array( 'toBlockIfelseif' => 4,
                                'keepIndexed'     => true);
        $this->checkAuto();

        // @doc if then { block } without else
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'If')),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_OPEN_CURLY',
                                              'atom'  => 'none'),
                                   5 => array('atom'  => array('Sequence', 'Void')),
                                   6 => array('token' => 'T_CLOSE_CURLY'),
                                   7 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'CONDITION',
                                                       3 => 'DROP',
                                                       4 => 'DROP',
                                                       5 => 'THEN',
                                                       6 => 'DROP'),
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => false),
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true);
        $this->checkAuto();

        // @doc if { sequence } then else { sequence }
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'If')),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_OPEN_CURLY',
                                              'atom'  => 'none'),
                                   5 => array('atom'  => array('Sequence', 'Void')),
                                   6 => array('token' => 'T_CLOSE_CURLY'),
                                   7 => array('token' => 'T_ELSE',
                                              'atom'  => 'none'),
                                   8 => array('token' => 'T_OPEN_CURLY'),
                                   9 => array('atom'  => array('Sequence', 'Void')),
                                   10 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'    => array(1  => 'DROP',
                                                       2  => 'CONDITION',
                                                       3  => 'DROP',
                                                       4  => 'DROP',
                                                       5  => 'THEN',
                                                       6  => 'DROP',
                                                       7  => 'DROP',
                                                       8  => 'DROP',
                                                       9  => 'ELSE',
                                                       10 => 'DROP'),
                               'atom'         => 'Ifthen',
                               'property'     => array('alternative' => false),
                               'makeSequence' => 'it',
                               'cleanIndex'   => true);
        $this->checkAuto();

        // @doc if then elseif (without else)
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'If')),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_OPEN_CURLY',
                                              'atom'  => 'none'),
                                   5 => array('atom'  => array('Sequence', 'Void')),
                                   6 => array('token' => 'T_CLOSE_CURLY'),
                                   7 => array('atom'  => 'Ifthen',
                                              'token' => 'T_ELSEIF',
                                              'property' => array('alternative' => false))
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'CONDITION',
                                                       3 => 'DROP',
                                                       4 => 'DROP',
                                                       5 => 'THEN',
                                                       6 => 'DROP',
                                                       7 => 'ELSE'
                                                      ),
                               'property'     => array('alternative' => false),
                               'makeSequence' => 'it',
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true
                               );
        $this->checkAuto();

        // @doc if then { block } without else (but within a else alternatif)
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'If')),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_OPEN_CURLY'),
                                   5 => array('atom'  => 'Sequence'),
                                   6 => array('token' => 'T_CLOSE_CURLY'),
                                   7 => array('token' => array('T_ELSE', 'T_ELSEIF')),
                                   8 => array('token' => 'T_COLON'),
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'CONDITION',
                                                       3 => 'DROP',
                                                       4 => 'DROP',
                                                       5 => 'THEN',
                                                       6 => 'DROP'),
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => false),
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true);
        $this->checkAuto();

    ////////////////////////////////////////////////////////////
    //// Alternative syntax                                 ////
    ////////////////////////////////////////////////////////////
                
    // @doc if () : endif (empty )
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'If')),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   5 => array('token' => array('T_ENDIF', 'T_ELSEIF', 'T_ELSE')),
        );
        
        $this->actions = array('insertVoid'  => 4,
                               'keepIndexed' => true,
                               'property'    => array('alternative' => true),
                               'cleanIndex'  => true);
        $this->checkAuto();

        // Make a block from sequence after a if/elseif (alternative syntax)
        $this->conditions = array(  0 => array('token'     => self::$operators,
                                               'atom'      => 'none'),
                                    1 => array('token' => 'T_OPEN_PARENTHESIS',
                                               'property' => array('association' => 'If')),
                                    2 => array('atom'  => 'yes'),
                                    3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    4 => array('token'     => array('T_COLON', 'T_SEMICOLON')),
                                    5 => array('notAtom'   => 'Sequence',
                                               'atom'      => 'yes'),
                                    6 => array('token'     => 'T_SEMICOLON',
                                               'atom'      => 'none'),
                                    7 => array('token'     => array('T_ELSEIF', 'T_ENDIF', 'T_ELSE'))
        );
        
        $this->actions = array( 'toBlockIfelseifAlternative' => 5,
                                'property'                   => array('alternative' => true),
                                'keepIndexed'                => true);
        $this->checkAuto();

    // @doc if ( ) : endif
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'If')),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   5 => array('atom'  => 'yes'),
                                   6 => array('token' => 'T_ENDIF'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'CONDITION',
                                                        3 => 'DROP',
                                                        4 => 'DROP',
                                                        5 => 'THEN',
                                                        6 => 'DROP'),
                               'property'     => array('alternative' => true),
                               'atom'         => 'Ifthen',
                               'makeSequence' => 'it',
                               'cleanIndex'   => true
                               );
        $this->checkAuto();

    // @doc if ( ) : else: endif (alternative syntax)
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'If')),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   5 => array('atom'  => 'yes'),
                                   6 => array('token' => 'T_ELSE'),
                                   7 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   8 => array('atom'  => 'yes'),
                                   9 => array('token' => array('T_ENDIF', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'CONDITION',
                                                        3 => 'DROP',
                                                        4 => 'DROP',
                                                        5 => 'THEN',
                                                        6 => 'DROP',
                                                        7 => 'DROP',
                                                        8 => 'ELSE',
                                                        9 => 'DROP',
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
                                   1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'If')),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_COLON',
                                              'property' => array('relatedAtom' => 'Ifthen')),
                                   5 => array('atom'  => 'yes'),
                                   6 => array('atom'  => 'Ifthen',
                                              'token' => 'T_ELSEIF',
                                              'property' => array('alternative' => true) ),
                                   7 => array('filterOut2' => 'T_ENDIF'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'CONDITION',
                                                        3 => 'DROP',
                                                        4 => 'DROP',
                                                        5 => 'THEN',
                                                        6 => 'ELSE',
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
    fullcode.fullcode = fullcode.code + " (" + fullcode.out("CONDITION").next().fullcode + ") : " + fullcode.out("THEN").next().fullcode + ' endif';
} else {
    fullcode.fullcode = fullcode.code + " (" + fullcode.out("CONDITION").next().fullcode + ") " + fullcode.out("THEN").next().fullcode;
}

GREMLIN;
    }

}

?>
