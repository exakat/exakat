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

namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class SelfTransform extends Analyzer {
    public function analyze(): void {
        // $x = strtolower($x);
        // $x = A.$x.$b;
        // First step : marks variables in the right part
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs(self::CONTAINERS)
             ->savePropertyAs('fullcode', 'left')
             ->back('first')

             ->outIs('RIGHT')
             ->atomInsideNoDefinition(self::CONTAINERS)
             ->samePropertyAs('fullcode', 'left');
        $this->prepareQuery();

        // Second step : marks variables in the left part
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs(self::CONTAINERS)
             ->savePropertyAs('fullcode', 'left')
             ->as('results')
             ->back('first')

             ->outIs('RIGHT')
             ->atomInsideNoDefinition(self::CONTAINERS)
             ->samePropertyAs('fullcode', 'left')
             ->back('results');
        $this->prepareQuery();

        // short assignement case
        $this->atomIs('Assignation')
             ->codeIs(array('.=', '+=', '-=', '%=', '&&=', '&=', '*=', '**=', '/='))
             ->outIs('LEFT')
             ->atomIs(self::CONTAINERS);
        $this->prepareQuery();

    }
}

?>
