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

namespace Exakat\Analyzer\Complete;

use Exakat\Analyzer\Analyzer;

class SolveTraitMethods extends Analyzer {
    public function analyze() {
        $this->atomIs('Usetrait', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs('As', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->atomIs('Staticmethod', Analyzer::WITHOUT_CONSTANTS)
              ->_as('results')
              ->tokenIs('T_STRING')
              ->savePropertyAs('lccode', 'methode')
              ->back('first')
              ->outIs('USE')
              ->inIs('DEFINITION')
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'methode', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'results')
              ->count();
        $this->rawQuery();

        $this->atomIs('Usetrait', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs('As', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->_as('results')
              ->atomIs('Nsname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'methode')
              ->back('first')
              ->outIs('USE')
              ->inIs('DEFINITION')
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'methode', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'results')
              ->count();
        $this->rawQuery();
    }
}

?>
