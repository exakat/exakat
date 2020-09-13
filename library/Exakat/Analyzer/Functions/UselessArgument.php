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

class UselessArgument extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateCalls',
                     'Complete/FollowClosureDefinition',
                    );
    }

    public function analyze(): void {
        // function foo($a)
        // foo(2); foo(2); foo(2); // always provide the same arg
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->back('first')

             // More than 2 calls to the method 
             ->filter(
                $this->side()
                     ->outIs('DEFINITION')
                     ->raw('count().is(gt(2))')
             )

            // When a non-literal value is found, it is registered in the final stats ($x => 1, 2 => 1) and make the filter fail automatically.
            // constants are used by value with the optional step.
             ->filter(
                $this->side()
                     ->initVariable('x', '[:]')
                     ->outIs('DEFINITION')
                     ->outIsIE('METHOD')
                     ->outWithRank('ARGUMENT', 'ranked')
                     ->optional(
                        $this->side()
                             ->atomIs(array('Integer', 'Float', 'String', 'Boolean', 'Null', 'Arrayliteral'), self::WITH_CONSTANTS)
                     )
                     ->raw('sideEffect{x[it.get().value("code")] = 1;}.fold().filter{ x.size() == 1;}')
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
