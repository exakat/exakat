<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
namespace Analyzer\Structures;

use Analyzer;

class SameConditions extends Analyzer\Analyzer {
    public function analyze() {
        $steps = 'out("ELSE").transform{ if (it.atom == "Sequence" && it.count == 1) { it.out("ELEMENT").has("rank", 0).next(); } else { it; } }';

        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->savePropertyAs('fullcode', 'condition')
             ->_as('results')
             ->inIs('CONDITION')
             ->filter(' it.as("x").'.$steps.'.loop("x"){ it.object.'.$steps.'.any() }{it.object.out("CONDITION").any()}.out("CONDITION").has("fullcode", condition).any()')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
