<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class InfiniteRecursion extends Analyzer {
    public function dependsOn() {
        return array('Functions/Recursive',
                    );
    }
    
    public function analyze() {
        // foo($a, $b) { foo($a, $b); }
        $this->atomIs('Function')
             ->analyzerIs('Functions/Recursive')
             ->isNot('count', 0) //Except at least one parameter
             ->not(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->outIs('NAME')
                     ->outIs('DEFINITION')
                     ->is('isModified', true)
             )
             ->collectArguments('args')
             ->outIs('DEFINITION')
             ->atomIs(self::$FUNCTIONS_CALLS)
             ->outIsIE('METHOD')
             ->collectArguments('called')
             ->filter('args.equals(called)')
             ->back('first');
        $this->prepareQuery();

        // foo($a, $b) { foo($a, $b); }
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIs('Functions/Recursive')
             ->isNot('count', 0) //Except at least one parameter
             ->not(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->outIs('NAME')
                     ->outIs('DEFINITION')
                     ->is('isModified', true)
             )
             ->collectArguments('args')
             ->outIs('DEFINITION')
             ->atomIs(self::$FUNCTIONS_CALLS)
             ->outIs(array('OBJECT', 'CLASS')) // Only works on static calls, if the class is a variable : $a::foo();
             ->isThis()
             ->inIs(array('OBJECT', 'CLASS'))
             ->outIsIE('METHOD')
             ->collectArguments('called')
             ->filter('args.equals(called)')
             ->back('first');
        $this->prepareQuery();

        // foo() { foo(); } // No argument
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->savePropertyAs('id', 'start')
             ->analyzerIs('Functions/Recursive')
             ->is('count', 0) 
             ->outIs('DEFINITION')
             ->atomIs(self::$FUNCTIONS_CALLS)
             ->outIsIE('METHOD')
             ->is('count', 0)
             ->goToFunction()
             ->samePropertyAs('id', 'start');
        $this->prepareQuery();

        // foo($a, $b) { foo($a, $b); } // No condition of any kind...
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIsNot('self')
             ->analyzerIs('Functions/Recursive')
             ->noAtomInside(array('Ifthen', 'Ternary'));
        $this->prepareQuery();
        
        // recursion level 2?
    }
}

?>
