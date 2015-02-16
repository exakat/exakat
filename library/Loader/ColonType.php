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
    private $waitFor = array('string' => array('?' => 'Ternary'),
                             'array'  => array('T_IF' => 'Ifthen',
                                               'T_ELSEIF' => 'Ifthen',
                                               'T_WHILE' => 'While',
                                               'T_FOREACH' => 'Foreach',
                                               'T_FOR' => 'For',
                                               'T_SWITCH' => 'Switch',

                                               'T_CASE' => '_Case',
                                               'T_DEFAULT' => '_Default',

                                               'T_ELSE' => 'Ifthen', 
                                               'T_DECLARE' => 'Declare', 
                                               ));
    private $parenthesisStack = array();
    private $relatedAtom = array('Label');
    private $parenthesisLevel = 0;
    private $checkNext = false;
    private $parenthesedToken = array('T_IF' => 1, 
                                      'T_ELSEIF' => 1,
                                      'T_SWITCH' => 1,
                                      'T_FOREACH' => 1,
                                      'T_WHILE' => 1,
                                      'T_FOR' => 1,
                                      'T_DECLARE' => 1);
    
    public function surveyToken($token) {
        if ($this->checkNext) {
            $this->checkNext = false;
            
            if (!is_string($token) || $token != ':') {
                // dropping non-alternative if.
                $a = array_pop($this->relatedAtom);
                if (count($this->relatedAtom) == 0) {
//                    print "relatedAtom is Empty\n";
                }
//                print "Check for Next ($a)\n";
//                  print_r($this->relatedAtom);
            }
        }

        if (is_array($token)) {
            if (isset($this->waitFor['array'][$token[3]])) {
                $this->relatedAtom[] = $this->waitFor['array'][$token[3]];
//                print "[] = ".$this->waitFor['array'][$token[3]]."\n";
                
                if (isset($this->parenthesedToken[$token[3]])) {
                    $this->parenthesisStack[$this->parenthesisLevel] = 1;
                }
                
                if ($token[3] == 'T_ELSE') {
//                    print_r($this->relatedAtom);
//                    print "T_ELSE ++\n";
                    $this->checkNext = true;
                }
            }
        } else {
            if (isset($this->waitFor['string'][$token])) {
                $this->relatedAtom[] = $this->waitFor['string'][$token];
            }
            
            if ($token == '(') {
                $this->parenthesisLevel++;
            } elseif ($token == ')') {
                $this->parenthesisLevel--;
                if (isset($this->parenthesisStack[$this->parenthesisLevel])) {
//                    print_r($this->parenthesisStack);
//                    print_r($this->parenthesisLevel);
                    $this->checkNext = true;
                    unset($this->parenthesisStack[$this->parenthesisLevel]);
                }
            } 
        }
        return null;
    }
    
    public function characterizeToken() {
        $r = array('relatedAtom', array_pop($this->relatedAtom));
        if (count($this->relatedAtom) == 0) {
            $this->relatedAtom = array('Label');
        }
        
//        print "related atom Is $r[1]\n";
        
        return $r;
    }
    
    public function dump() {
//        print_r($this->relatedAtom);
//        print_r($this->parenthesisStack);
    }

}