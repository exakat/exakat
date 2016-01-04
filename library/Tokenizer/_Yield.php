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

class _Yield extends TokenAuto {
    static public $operators = array('T_YIELD');
    static public $atom = 'Yield';

    public function _check() {
            $this->conditions = array(0 => array('token' => _Yield::$operators,
                                                 'atom'  => 'none'),
                                      1 => array('token' => array_merge(Preplusplus::$operators,
                                                                        Postplusplus::$operators,
                                                                        Assignation::$operators,
                                                                        Addition::$operators,
                                                                        Multiplication::$operators,
                                                                        Preplusplus::$operators,
                                                                        Concatenation::$operators,
                                                                        Comparison::$operators,
                                                                        Bitshift::$operators,
                                                                        Property::$operators,
                                                                        Staticproperty::$operators,
                                                                        _Instanceof::$operators,
                                                                        Ternary::$operators,
                                                                        array('T_CLOSE_PARENTHESIS', 'T_SEMICOLON', 'T_CLOSE_TAG')),
                                                  'atom' => 'none')
                                    );

        $this->actions = array('addEdge'     => array(0 => array('Arguments' => 'ARGUMENT')),
                               'keepIndexed' => true);
        $this->checkAuto();

        $config = \Config::factory();
        if (version_compare('7.0', $config->phpversion) >= 0) {
            // PHP 7.0 and +
            $this->conditions = array(0 => array('token' => _Yield::$operators,
                                                 'atom'  => 'none'),
                                      1 => array('atom'  => 'yes'),
                                      2 => array('token' => array_merge( array('T_SEMICOLON', 'T_CLOSE_PARENTHESIS', 'T_CLOSE_TAG'),
                                                                         Addition::$operators,
                                                                         Logical::$operators,
                                                                         Comparison::$operators)
                                                )
                                      );
        } else {
            // PHP 5.6 and -
            $this->conditions = array(0 => array('token' => _Yield::$operators,
                                                 'atom'  => 'none'),
                                      1 => array('atom'  => 'yes'),
                                      2 => array('token' => array_merge( array('T_SEMICOLON', 'T_CLOSE_PARENTHESIS', 'T_CLOSE_TAG'),
                                                                         Addition::$operators)
                                                )
                                      );
        }
        
        $this->actions = array('transform'    => array( 1 => 'YIELD' ),
                               'cleanIndex'   => true,
                               'atom'         => 'Yield',
                               'addSemicolon' => 'it');
                               
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
fullcode.setProperty('fullcode', "yield " + fullcode.out("YIELD").next().getProperty('fullcode'));
GREMLIN;
    }
}

?>
