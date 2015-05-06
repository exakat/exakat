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

class _While extends TokenAuto {
    static public $operators = array('T_WHILE');
    static public $atom = 'While';

    public function _check() {
         // While( condition ) ;
        $this->conditions = array( 0 => array('token'     => _While::$operators,
                                              'dowhile'   => false),
                                   1 => array('token'     => 'T_OPEN_PARENTHESIS',
                                              'property'  => array('association' => 'While')),
                                   2 => array('atom'      => 'yes'),
                                   3 => array('token'     => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token'     => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'      => 'none'),
        );
        
        $this->actions = array('addEdge'     => array(4 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto();

         //  syntax   While() $x++;
        $this->conditions = array( 0 => array('token'      => _While::$operators,
                                              'dowhile'    => false),
                                   1 => array('token'      => 'T_OPEN_PARENTHESIS',
                                              'property'   => array('association' => 'While')),
                                   2 => array('atom'       => 'yes'),
                                   3 => array('token'      => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('atom'       => 'yes',
                                              'notAtom'    => 'Sequence'),
                                   5 => array('filterOut2' => Token::$instructionEnding),
        );
        
        $this->actions = array('toWhileBlock' => true,
                               'keepIndexed'  => true);
        $this->checkAuto();
        
         //  While( ) { normal code ; }
       $this->conditions = array( 0 => array('token'     => _While::$operators,
                                             'dowhile'   => false),
                                  1 => array('token'     => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'While')),
                                  2 => array('atom'      => 'yes'),
                                  3 => array('token'     => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token'     => 'T_OPEN_CURLY'),
                                  5 => array('atom'      => 'yes'),
                                  6 => array('token'     => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array('transform'    => array(  1 => 'DROP',
                                                         2 => 'CONDITION',
                                                         3 => 'DROP',
                                                         4 => 'DROP',
                                                         5 => 'BLOCK',
                                                         6 => 'DROP'),
                               'makeSequence' => 'it',
                               'atom'         => 'While',
                               'cleanIndex'   => true,
                               'makeBlock'    => 'BLOCK');
        $this->checkAuto();
        
        // alternative syntax While( ) : endwhile
        $this->conditions = array(0 => array('token'   => _While::$operators,
                                             'dowhile' => false),
                                  1 => array('token'   => 'T_OPEN_PARENTHESIS',
                                              'property' => array('association' => 'While')),
                                  2 => array('atom'    => 'yes'),
                                  3 => array('token'   => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token'   => 'T_COLON',
                                              'property' => array('association' => 'While')),
                                  5 => array('atom'    => 'yes'),
                                  6 => array('token'   => 'T_ENDWHILE'),
        );
        
        $this->actions = array('transform'    => array(  1 => 'DROP',
                                                         2 => 'CONDITION',
                                                         3 => 'DROP',
                                                         4 => 'DROP',
                                                         5 => 'BLOCK',
                                                         6 => 'DROP',
                                                        ),
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => true),
                               'atom'         => 'While',
                               'cleanIndex'   => true);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (it.alternative == true) {
    fullcode.setProperty('fullcode', "while (" + fullcode.out("CONDITION").next().getProperty('fullcode') + ") : " + fullcode.out("BLOCK").next().getProperty('fullcode') + ' endwhile');
} else {
    fullcode.setProperty('fullcode', "while (" + fullcode.out("CONDITION").next().getProperty('fullcode') + ") " + fullcode.out("BLOCK").next().getProperty('fullcode'));
}

GREMLIN;

    }

}

?>
