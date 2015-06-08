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


namespace Loader;

class ColonType {
    private $waitFor = array('string' => array('?'         => 'Ternary'),
                             'array'  => array('T_IF'      => 'Ifthen',
                                               'T_ELSEIF'  => 'Ifthen',
                                               'T_WHILE'   => 'While',
                                               'T_FOREACH' => 'Foreach',
                                               'T_FOR'     => 'For',
                                               'T_SWITCH'  => 'Switch',

                                               'T_CASE'    => 'Case',
                                               'T_DEFAULT' => 'Default',

                                               'T_ELSE'    => 'Ifthen', 
                                               'T_DECLARE' => 'Declare', 
                                               ));
    private $parenthesisStack = array();
    private $association = array('Label');
    private $parenthesisLevel = 0;
    private $checkNext = false;
    private $tokenWithParenthesis = array('T_IF'      => 1, 
                                          'T_ELSEIF'  => 1,
                                          'T_SWITCH'  => 1,
                                          'T_FOREACH' => 1,
                                          'T_WHILE'   => 1,
                                          'T_FOR'     => 1,
                                          'T_DECLARE' => 1);
    
    public function surveyToken($token) {
        if ($this->checkNext) {
            $this->checkNext = false;
            
            if (!is_string($token) || $token != ':') {
                // dropping non-alternative if.
                array_pop($this->association);
            }
        }

        if (is_array($token)) {
            if (isset($this->waitFor['array'][$token[3]])) {
                $this->association[] = $this->waitFor['array'][$token[3]];
                
                if (isset($this->tokenWithParenthesis[$token[3]])) {
                    $this->parenthesisStack[$this->parenthesisLevel] = 1;
                }
                
                if ($token[3] == 'T_ELSE') {
                    $this->checkNext = true;
                }
            }
        } else {
            if (isset($this->waitFor['string'][$token])) {
                $this->association[] = $this->waitFor['string'][$token];
            }
            
            if ($token == '(') {
                $this->parenthesisLevel++;
            } elseif ($token == ')') {
                $this->parenthesisLevel--;
                if (isset($this->parenthesisStack[$this->parenthesisLevel])) {
                    $this->checkNext = true;
                    unset($this->parenthesisStack[$this->parenthesisLevel]);
                }
            } 
        }
        return null;
    }
    
    public function characterizeToken() {
        $r = array('association', array_pop($this->association));
        if (count($this->association) == 0) {
            $this->association = array('Label');
        }
        
        return $r;
    }
}

?>