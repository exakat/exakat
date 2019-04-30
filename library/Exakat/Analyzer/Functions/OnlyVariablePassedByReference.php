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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\GroupBy;

class OnlyVariablePassedByReference extends Analyzer {
    public function analyze() {
        // Functioncalls
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENT')
             ->atomIsNot(self::$CONTAINERS_PHP)
             ->not(
                $this->side()
                     ->atomIs(self::$FUNCTIONS_CALLS)
                     ->inIs('DEFINITION')
                     ->is('reference', true)
             )
             // Case for static method call ?
             ->savePropertyAs('rank', 'position')
             ->back('first')
             ->functionDefinition()
             ->outWithRank('ARGUMENT', 'position')
             ->is('reference', true)
             ->back('first');
        $this->prepareQuery();

        // methods calls
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'method')
             ->outIs('ARGUMENT')
             ->atomIsNot(self::$CONTAINERS_PHP)
             ->not(
                $this->side()
                     ->atomIs(self::$FUNCTIONS_CALLS)
                     ->inIs('DEFINITION')
                     ->is('reference', true)
             )
             // Case for static method call ?
             ->savePropertyAs('rank', 'position')
             ->back('first')
             ->inIs('DEFINITION')
             ->outWithRank('ARGUMENT', 'position')
             ->is('reference', true)
             ->back('first');
        $this->prepareQuery();

        // Static methods calls
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'method')
             ->outIs('ARGUMENT')
             ->atomIsNot(self::$CONTAINERS_PHP)
             ->not(
                $this->side()
                     ->atomIs(self::$FUNCTIONS_CALLS)
                     ->inIs('DEFINITION')
                     ->is('reference', true)
             )
             // Case for static method call ?
             ->savePropertyAs('rank', 'position')
             ->back('first')
             ->inIs('DEFINITION')
             ->outWithRank('ARGUMENT', 'position')
             ->is('reference', true)
             ->back('first');
        $this->prepareQuery();

        // Checking PHP Native functions
        $functions = self::$methods->getFunctionsReferenceArgs();
        $references = new GroupBy();

        foreach($functions as $function) {
            $references[$function['position']] = makeFullnspath($function['function']);
        }
        
//        $references = array(2 => $references[2]);

        foreach($references as $position => $functions) {
            // Functioncalls
            $this->atomFunctionIs($functions)
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIsNot(self::$CONTAINERS_PHP)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
