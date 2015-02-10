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

class Variable extends TokenAuto {
    static public $operators = array('T_DOLLAR_OPEN_CURLY_BRACES', 'T_CURLY_OPEN');
    static public $atom = 'Variable';
    
    public function _check() {
        // "  {$variable}  " or " ${x} "
        $this->conditions = array(0 => array('token' => Variable::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => String::$allowedClasses),
                                  2 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'to_variable' => 1,
                                'cleanIndex'  => true);
        $this->checkAuto();
        
        // todo find a way to process those remainings atom that may be found in those {}
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
        
it.fullcode = it.code;
if (it.reference == "true") {
    it.fullcode = "&" + it.fullcode;
}

x = it;
it.has("token", "T_STRING_VARNAME").each{ x.fullcode = "\\$" + it.code; }
it.has("token", "T_DOLLAR").filter{   it.out("NAME").next().atom in ["Variable", "Identifier"] }.out("NAME").each{ x.fullcode = "\\$" + it.fullcode; }
it.has("token", "T_DOLLAR").filter{ !(it.out("NAME").next().atom in ["Variable", "Identifier"])}.out("NAME").each{ x.fullcode = "\\${" + it.fullcode + "}"; }

it.has("token", "T_DOLLAR_OPEN_CURLY_BRACES").each{ x.fullcode = "\\${" + it.fullcode + "}"; }
it.has("token", "T_CURLY_OPEN").out("NAME").each{ x.fullcode = it.fullcode; }

GREMLIN;
    }
}

?>
