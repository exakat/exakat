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

class MismatchedDefaultArguments extends Analyzer {
    public function dependsOn() {
        return array('Complete/PropagateCalls',
                    );
    }

    public function analyze() {
        // Based on calls to a function
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->savePropertyAs('code', 'name')
             ->_as('results')
             ->outIs('DEFAULT')
             ->savePropertyAs('fullcode', 'defaultValue')
             ->back('results')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->has('rank')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->inIsIE('METHOD')
             ->atomIs(array('Functioncall', 'Methodcall', 'Staticmethodcall'))
             ->inIs('DEFINITION')
             ->checkDefinition()
             ->back('results');
        $this->prepareQuery();
    }
    
    private function checkDefinition() {
        $this->outWithRank('ARGUMENT', 'ranked')
             ->outIs('DEFAULT')
             ->notSamePropertyAs('fullcode', 'defaultValue');
        return $this;
    }
}

?>
