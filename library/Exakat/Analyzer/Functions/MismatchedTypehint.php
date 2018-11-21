<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class MismatchedTypehint extends Analyzer {
    public function analyze() {
        // Based on calls to a function
        $this->atomIs(array('Function', 'Method', 'Closure'))
             ->outIs('ARGUMENT')
             ->savePropertyAs('code', 'name')
             ->_as('results')
             ->outIs('TYPEHINT')
             ->savePropertyAs('fullnspath', 'typehint')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'name')
             ->has('rank')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->atomIs('Functioncall')
             ->inIs('DEFINITION')
             ->checkDefinition()
             ->back('results');
        $this->prepareQuery();

        // Based on Methodcalls : still missing the class of the object
        
        // Based on staticmethodcall
        $this->atomIs(array('Function', 'Method', 'Closure'))
             ->outIs('ARGUMENT')
             ->savePropertyAs('code', 'name')
             ->_as('results')
             ->outIs('TYPEHINT')
             ->savePropertyAs('fullnspath', 'typehint')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'name')
             ->has('rank')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->savePropertyAs('code', 'method')
             ->inIs('METHOD')
             ->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->inIs('DEFINITION')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->samePropertyAs('code', 'method')
             ->inIs('NAME')
             ->checkDefinition()
             ->back('results');
        $this->prepareQuery();
    }
    
    private function checkDefinition() {
        $this->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')
             ->outIs('TYPEHINT')
             ->notSamePropertyAs('fullnspath', 'typehint');
             
        return $this;
    }
}

?>
