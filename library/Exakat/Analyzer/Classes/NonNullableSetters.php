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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class NonNullableSetters extends Analyzer {
    public function dependsOn() {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze() {
        // class x { private $p = 1;
        //           function foo() : C { return $this->p; }}
        $this->atomIs('Method')
             ->outIs('RETURNTYPE')
             ->isNot('nullable', true)
             ->savePropertyAs('fullnspath', 'fnp')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))

             ->back('first')

             ->outIs('BLOCK')
             ->atomInside('Return')
             ->outIs(array('RETURN'))
             ->atomIs('Member')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->hasNoIn('RIGHT')

             ->not(
                $this->side()
                     ->has('fullnspath')
                     ->samePropertyAs('fullnspath', 'fnp')
             )
             ->back('first');
        $this->prepareQuery();

        // class x { private $p = 1;
        //           function foo() : C { return $this->p; }}
        $this->atomIs('Method')
             ->outIs('RETURNTYPE')
             ->isNot('nullable', true)
             ->savePropertyAs('fullnspath', 'fnp')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))

             ->back('first')

             ->outIs('BLOCK')
             ->atomInside('Return')
             ->outIs(array('RETURN'))
             ->atomIs('Member')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->inIs('DEFINITION')
             ->hasNoOut('DEFAULT')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
