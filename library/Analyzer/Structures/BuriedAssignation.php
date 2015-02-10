<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class BuriedAssignation extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Assignation')
             ->hasNoIn('ELEMENT')
             // in a While
             ->raw('filter{ it.in("CONDITION", "INIT").any() == false}')

             // in a IF
             ->raw('filter{ it.in("CODE").in("CONDITION").any() == false}')
             // in a if( ($a =2) !== 3) {}
             ->raw('filter{ it.in("CODE").in.has("atom", "Comparison").in("CODE").in("CONDITION").any() == false}')

             // in a chained assignation
             ->raw('filter{ it.in("RIGHT").has("atom", "Assignation").any() == false}')

             // in an argument (with or without typehint)
             ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").has("atom", "Function").any() == false}')
             ->raw('filter{ it.in("VARIABLE").in("ARGUMENT").in("ARGUMENTS").has("atom", "Function").any() == false}');
        $this->prepareQuery();
    }
}

?>
