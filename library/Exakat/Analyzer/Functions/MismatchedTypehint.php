<?php declare(strict_types = 1);
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

class MismatchedTypehint extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenMethods',
                     'Complete/MakeClassMethodDefinition',
                     'Complete/PropagateCalls',
                     'Complete/FollowClosureDefinition',
                    );
    }

    public function analyze(): void {
        // Based on calls to a function, method or static method
        // function foo(A $a) { bar($a);} function bar(B $a) {...}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('results')
             ->outIs('TYPEHINT')
             ->savePropertyAs('fullnspath', 'typehint')
             ->inIs('TYPEHINT')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->has('rank')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->inIsIE('METHOD')
             ->atomIs(self::CALLS)
             ->inIs('DEFINITION')
             ->checkDefinition()
             ->back('results');
        $this->prepareQuery();
    }

    private function checkDefinition() {
        $this->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'ranked')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->notSamePropertyAs('fullnspath', 'typehint')
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->goToAllParents(self::INCLUDE_SELF)
                     ->notSamePropertyAs('fullnspath', 'typehint')
             );

        return $this;
    }
}

?>
