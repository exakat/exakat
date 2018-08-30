<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class MismatchTypeAndDefault extends Analyzer {
    public function analyze() {
        // function foo(string $s = 3)
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->_as('arg')
             ->outIs('DEFAULT')
             ->outIsIE(array('THEN', 'ELSE', 'LEFT', 'RIGHT')) // basic handling of ternary
             ->goToLiteralValue()
             ->savePropertyAs('label', 'type')
             ->back('arg')
             ->outIs('TYPEHINT')
             ->raw(<<<GREMLIN
filter{
    switch(it.get().value("fullnspath")) {
        case '\\\\string' : 
            !(type in ["String", "Heredoc", "Concatenation", "Null"]);
            break;

        case '\\\\int' : 
            !(type in ["Integer", "Addition", "Multiplication", "Power", "Null"]);
            break;

        case '\\\\float' : 
            !(type in ["Float", "Integer", "Addition", "Multiplication", "Power", "Null"]);
            break;

        case '\\\\bool' : 
            !(type in ["Boolean", "Null", "Comparison", "Logical", "Not"]);
            break;

        case '\\\\array' : 
            !(type in ["Arrayliteral", "Addition", "Null"]);
            break;
        
        // callable
        // object
        // iterable
        // self, static
        default : 
            !(type in ["Null"]);
            break;
    }
}
GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
