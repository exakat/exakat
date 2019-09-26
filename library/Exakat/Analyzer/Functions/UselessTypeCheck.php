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

class UselessTypeCheck extends Analyzer {
    public function dependsOn() {
        return array('Complete/CreateDefaultValues',
                    );
    }

    public function analyze() {
        // function foo(A $a) { if (is_null($a)) {}}
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->isNot('nullable', true)

             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->inIs('TYPEHINT')

             ->not(
                $this->side()
                     ->outIs('DEFAULT')
                     ->atomis('Null')
                     ->hasNoIn('RIGHT')
             )
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('ARGUMENT')
             ->functioncallIs('\\is_null')
             ->back('first');
        $this->prepareQuery();

        // function foo(A $a) { if ($a === null) {}}
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->isNot('nullable', true)

             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->inIs('TYPEHINT')

             ->not(
                $this->side()
                     ->outIs('DEFAULT')
                     ->atomis('Null')
                     ->hasNoIn('RIGHT')
             )
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Null',self::WITH_CONSTANTS)
             ->back('first');
        $this->prepareQuery();

        // function foo(?A $a = null) { }
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->is('nullable', true)

             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->inIs('TYPEHINT')

             ->filter(
                $this->side()
                     ->outIs('DEFAULT')
                     ->atomIs('Null')
                     ->hasNoIn('RIGHT')
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
