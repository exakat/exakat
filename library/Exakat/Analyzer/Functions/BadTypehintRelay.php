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

class BadTypehintRelay extends Analyzer {
    public function analyze() : void {
        // todo : handle union typehint :
        // todo : handle class hierarchy
        // todo : handle relay via local variables

        // foo(A $a) { goo($a); } function goo(B $a) {}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->savePropertyAs('fullnspath', 'typehint')
             ->inIs('TYPEHINT')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->has('rank')
             ->savePropertyAs('rank', 'theRank')
             ->inIs('ARGUMENT')
             ->atomIs(self::CALLS)
             ->inIs('DEFINITION')
             ->as('result')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'theRank')
             ->not(
                $this->side()
                     ->outIs('TYPEHINT')
                     ->samePropertyAs('fullnspath', 'typehint')
             )
             ->back('result');
        $this->prepareQuery();

        // foo() : int { return goo(); } function goo() : B
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->savePropertyAs('fullnspath', 'typehint')
             ->back('first')

             ->outIs('DEFINITION')
             ->inIs('RETURN')
             ->goToFunction()
             ->not(
                $this->side()
                     ->outIs('RETURNTYPE')
                     ->samePropertyAs('fullnspath', 'typehint')
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
