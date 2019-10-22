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
use Exakat\Data\Methods;

class CouldTypeWithBool extends Analyzer {
    public function dependsOn() {
        return array('Complete/PropagateCalls',
                    );
    }

    public function analyze() {

        // function foo($a) { $a && 1; }
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Logical'))
             ->back('first');
        $this->prepareQuery();

        // function foo($a) { $a === false; }
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Comparison'))
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Boolean', self::WITH_CONSTANTS)
             ->back('first');
        $this->prepareQuery();

        // function foo($a) { !$a;  if ($a) {} }
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->hasIn(array('NOT', 'CONDITION')) 
             ->back('first');
        $this->prepareQuery();

        // function foo($a) { }; only foo(1)
        // function foo($a) { }; only foo((int))
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->savePropertyAs('rank', 'ranked')
             // called as bool
             ->filter(
                $this->side()
                     ->inIs('ARGUMENT')
                     ->outIs('DEFINITION')
                     ->atomIs(self::$CALLS)
                     ->outWithRank('ARGUMENT', 'ranked')
                     ->atomIs(array('Comparison', 'Logical', 'Boolean', 'Cast'))
             )
             // not called as non-bool
             ->not(
                $this->side()
                     ->inIs('ARGUMENT')
                     ->outIs('DEFINITION')
                     ->atomIs(self::$CALLS)
                     ->outWithRank('ARGUMENT', 'ranked')
                     ->atomIsNot(array('Comparison', 'Logical'))
                     ->tokenIsNot('T_BOOL_CAST')
             )
             ->back('first');
        $this->prepareQuery();

        // function foo($a) { bar($a); } function bar(int $b) {}
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->inIs('DEFINITION')
             ->outWithRank('ARGUMENT', 'ranked')
             ->outIs('TYPEHINT')
             ->fullnspathIs('\\bool')
             ->back('first');
        $this->prepareQuery();

        // function foo($a) { chr($a); } 
        $natives = self::$methods->getFunctionsByArgType('bool', Methods::STRICT);

        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->atomIs('Functioncall')
             ->has('fullnspath')
             ->hasNoIn('DEFINITION')
             ->isHash('fullnspath', $natives, 'ranked')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
