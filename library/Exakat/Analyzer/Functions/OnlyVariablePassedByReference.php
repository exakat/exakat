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
use Exakat\Data\GroupBy;

class OnlyVariablePassedByReference extends Analyzer {
    public function analyze() {
        $containers = array('Variable', 'Phpvariable', 'Member', 'Staticproperty', 'Array');
        $notReferenceReturningFunction = 'not( where(__.hasLabel("Functioncall").in("DEFINITION").has("reference", true)) )';

        // Functioncalls
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENT')
             ->atomIsNot($containers)
             ->raw($notReferenceReturningFunction)
             // Case for static method call ?
             ->savePropertyAs('rank', 'position')
             ->back('first')
             ->functionDefinition()
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'position')
             ->is('reference', true)
             ->back('first');
        $this->prepareQuery();

        // Static methods calls
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'method')
             ->outIs('ARGUMENT')
             ->atomIsNot($containers)
             ->raw($notReferenceReturningFunction)
             // Case for static method call ?
             ->savePropertyAs('rank', 'position')
             ->back('first')
             ->outIs('CLASS')
             ->classDefinition()
             ->outIs('METHOD')
             ->atomIs('Method')
             ->samePropertyAs('code', 'method')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'position')
             ->is('reference', true)
             ->back('first');
        $this->prepareQuery();

        // Checking PHP Native functions
        $functions = $this->methods->getFunctionsReferenceArgs();
        $references = new GroupBy();
        
        foreach($functions as $function) {
            $references[$function['position']] = '\\'.$function['function'];
        }

        foreach($references as $position => $functions) {
            // Functioncalls
            $this->atomIs('Functioncall')
                 ->fullnspathIs($functions)
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIsNot($containers)
//                 ->raw($notReferenceReturningFunction)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
