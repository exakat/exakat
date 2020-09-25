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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UsedOnceProperty extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenProperties',
                    );
    }

    public function analyze(): void {
        // class x { private $p = 1; function foo() {$this->p = 1;} }
        $this->atomIs('Ppp')
             ->isNot('visibility', 'public')
             ->outIs('PPP')
             ->atomIsNot('Virtualproperty')
             ->as('results')
             // Check the local results ()
             ->filter(
                $this->side()
                     ->goToAllDefinitions()
                     ->outIs('DEFINITION')
                     ->raw('count().is(eq(1))')
             )
             // Check the parent classes (should be 0 too)
             ->filter(
                $this->side()
                     ->outIs('OVERWRITE')
                     ->outIs('DEFINITION')
                     ->raw('count().is(eq(0))')
             );
        $this->prepareQuery();
    }
}

?>
