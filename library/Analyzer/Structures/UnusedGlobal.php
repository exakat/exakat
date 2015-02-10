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

class UnusedGlobal extends Analyzer\Analyzer {
    public function analyze() {
        // global in a function
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Variable')
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->savePropertyAs('id', 'theGlobalId')
             ->goToFunction()
             ->raw('filter{ it.out("BLOCK").out.loop(1){true}{it.object.atom == "Variable"}.has("code", theGlobal).hasNot("id", theGlobalId).any() == false}')
             ->back('result');
        $this->prepareQuery();

        // global in the global space
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Variable')
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->savePropertyAs('id', 'theGlobalId')
             ->notInFunction()
             ->goToFile()
             ->raw('filter{ it.out("FILE").out("ELEMENT").out("CODE").out.loop(1){!(it.object.atom in ["Class", "Function", "Trait", "Interface"])}{it.object.atom == "Variable"}.has("code", theGlobal).hasNot("id", theGlobalId).any() == false}')
             ->raw('filter{ it.out("FILE").out("CODE").out.loop(1){!(it.object.atom in ["Class", "Function", "Trait", "Interface"])}{it.object.atom == "Variable"}.has("code", theGlobal).hasNot("id", theGlobalId).any() == false}')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
