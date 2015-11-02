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
        // Build the condition
        $this->conditions = array( 0 => array('token'     => self::$operators),
                                   1 => array('token'     => 'T_OPEN_PARENTHESIS',
                                              'property'  => array('association' => 'If')),
                                   2 => array('atom'      => 'yes'),
                                   3 => array('token'     => 'T_CLOSE_PARENTHESIS')
        );

        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'CONDITION',
                                                       3 => 'DROP'),
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto();

        // @doc if () with only ;
        $this->conditions = array( 0 => array('token' => self::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_SEMICOLON',
                                              'atom'  => 'none')
        );
        
        $this->actions = array('addEdge'     => array(1 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto();

        // @doc if () $x++;
        // Make a block from sequence after a if/elseif
        $this->conditions = array(  0 => array('token'   => self::$operators,
                                               'atom'    => 'none'),
                                    1 => array('notAtom' => 'Sequence',
                                               'notToken'=> 'T_ELSEIF',
                                               'atom'    => 'yes'),
                                    2 => array('token'   => array('T_SEMICOLON', 'T_ELSEIF', 'T_ELSE', 'T_IF',
                                                                  'T_ENDIF', 'T_CLOSE_TAG', 'T_INLINE_HTML', 'T_END',
                                                                  'T_CLOSE_CURLY', 'T_ENDFOREACH', 'T_ENDSWITCH',
                                                                  'T_ENDFOR', 'T_ENDWHILE', 'T_ENDDECLARE', 'T_VOID')),
        );
        
        $this->actions = array( 'toBlockIfelseif' => 1,
                                'keepIndexed'     => true);
        $this->checkAuto();

        // @doc if () /**/ else $x++;
        // Make a block from sequence after else
        $this->conditions = array(  0 => array('token'   => self::$operators,
                                               'atom'    => 'none'),
                                    1 => array('token'   => 'T_ELSE'),
                                    2 => array('atom'    => 'yes'),
                                    3 => array('token'   => array('T_SEMICOLON', 'T_ELSEIF', 'T_ELSE', 'T_IF',
                                                                  'T_ENDIF', 'T_CLOSE_TAG', 'T_INLINE_HTML', 'T_END',
                                                                  'T_CLOSE_CURLY', 'T_ENDFOREACH', 'T_ENDSWITCH',
                                                                  'T_ENDFOR', 'T_ENDWHILE', 'T_ENDDECLARE', 'T_VOID')),
        );
        
        $this->actions = array( 'toBlockElse' => 1,
                                'keepIndexed' => true);
        $this->checkAuto();

        // Finish the THEN block
        $this->conditions = array( 0 => array('token'    => self::$operators),
                                   1 => array('token'    => 'T_OPEN_CURLY',
                                              'atom'     => 'none'),
                                   2 => array('atom'     => array('Sequence', 'Void')),
                                   3 => array('token'    => 'T_CLOSE_CURLY'),
                                   4 => array('notToken' => array('T_ELSE', 'T_ELSEIF'))
        );
        
        $this->actions = array('transform'          => array(1 => 'DROP',
                                                             2 => 'THEN',
                                                             3 => 'DROP'),
                               'cleanIndex'         => true,
                               'atom'               => 'Ifthen',
                               'addAlwaysSemicolon' => 'it'
                               );
        $this->checkAuto();

        // Build the THEN block
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_OPEN_CURLY',
                                              'atom'  => 'none'),
                                   2 => array('atom'  => array('Sequence', 'Void')),
                                   3 => array('token' => 'T_CLOSE_CURLY'),
                                   4 => array('token' => array('T_ELSE', 'T_ELSEIF'))
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'THEN',
                                                       3 => 'DROP'),
                               'keepIndexed'  => true,
                               'cleanIndex'   => true
                               );
        $this->checkAuto();

        // Build the ELSE block
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_ELSE',
                                              'atom'  => 'none'),
                                   2 => array('token' => 'T_OPEN_CURLY',
                                              'atom'  => 'none'),
                                   3 => array('atom'  => array('Sequence', 'Void')),
                                   4 => array('token' => 'T_CLOSE_CURLY')
        );
        
        $this->actions = array('transform'          => array(1 => 'DROP',
                                                             2 => 'DROP',
                                                             3 => 'ELSE',
                                                             4 => 'DROP'),
                               'cleanIndex'         => true,
                               'atom'               => 'Ifthen',
                               'addAlwaysSemicolon' => 'it');
        $this->checkAuto();

        // Build the ELSEIF block
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_ELSEIF',
                                              'atom'  => 'Ifthen')
        );
        
        $this->actions = array('transform'    => array(1 => 'ELSE'),
                               'cleanIndex'   => true,
                               'atom'         => 'Ifthen',
                               'addSemicolon' => 'it');
        $this->checkAuto();
        
        ////////////////////////////////////////////////////////////
        // alternative syntax
        ////////////////////////////////////////////////////////////

        // Finish the THEN block
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_COLON'),
                                   2 => array('atom'  => array('Sequence', 'Void')),
                                   3 => array('token' => 'T_ENDIF')
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'THEN',
                                                       3 => 'DROP'),
                               'cleanIndex'   => true,
                               'property'     => array('alternative' => true),
                               'atom'         => 'Ifthen',
                               'addSemicolon' => 'it'
                               );
        $this->checkAuto();

        // Finish the THEN block with ELSEIF
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_COLON'),
                                   2 => array('atom'  => array('Sequence', 'Void')),
                                   3 => array('token' => 'T_ELSEIF',
                                              'atom'  => 'yes')
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'THEN',
                                                       3 => 'ELSE'),
                               'cleanIndex'   => true,
                               'property'     => array('alternative' => true),
                               'atom'         => 'Ifthen',
                               'addSemicolon' => 'it'
                               );
        $this->checkAuto();

        // build the THEN block
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_COLON'),
                                   2 => array('atom'  => array('Sequence', 'Void')),
                                   3 => array('token' => 'T_ELSE')
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'THEN'),
                               'cleanIndex'   => true,
                               'keepIndexed'  => true,
                               'property'     => array('alternative' => true),
                               );
        $this->checkAuto();

        // Finish the ELSE block
        $this->conditions = array( 0 => array('token' => self::$operators),
                                   1 => array('token' => 'T_ELSE'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => array('Sequence', 'Void')),
                                   4 => array('token' => 'T_ENDIF')
        );
        
        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'DROP',
                                                       3 => 'THEN',
                                                       4 => 'DROP'),
                               'cleanIndex'   => true,
                               'property'     => array('alternative' => true),
                               'atom'         => 'Ifthen',
                               'addSemicolon' => 'it'
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.alternative == true) {
    then = fullcode.out("THEN").next();
    fullcode.fullcode = fullcode.code + " (" + fullcode.out("CONDITION").next().fullcode + ") : " + then.fullcode;
    if (fullcode.out('ELSE').any()) {
        theElse = fullcode.out("ELSE").next();
        if (theElse.token == 'T_ELSEIF') {
            fullcode.fullcode = fullcode.fullcode + " " + theElse.fullcode;
        } else {
            fullcode.fullcode = fullcode.fullcode + " else : " + fullcode.out("ELSE").next().fullcode;
            fullcode.fullcode = fullcode.fullcode + ' endif';
        }
    } else {
        fullcode.fullcode = fullcode.fullcode + ' endif'
    }
} else {
    theThen = fullcode.out("THEN").next();
    if (theThen.bracket == false) {
        fullcode.fullcode = fullcode.code + " (" + fullcode.out("CONDITION").next().fullcode + ") " + theThen.fullcode;
    } else {
        fullcode.fullcode = fullcode.code + " (" + fullcode.out("CONDITION").next().fullcode + ") { /**/ }";
    }
    
    if (fullcode.out('ELSE').any()) {
        theElse = fullcode.out("ELSE").next();
        if (theElse.token == 'T_ELSEIF') {
            fullcode.fullcode = fullcode.fullcode + " " + theElse.fullcode;
        } else if (theThen.bracket == false) {
            fullcode.fullcode = fullcode.fullcode + " else " + theElse.fullcode;
            theElse.count = theElse.out('ELEMENT').count();
        } else {
            fullcode.fullcode = fullcode.fullcode + " else { /**/ }";
            theElse.count = theElse.out('ELEMENT').count();
        }
    }
}

GREMLIN;
    }

}

?>
