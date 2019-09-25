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

class ShouldBeTypehinted extends Analyzer {
    public function analyze() {
        // spotting objects with property
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->hasNoIn('RIGHT')
             ->back('first')
             
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('OBJECT')
             ->atomIs(array('Methodcall', 'Member', 'Staticproperty', 'Staticclass', 'Staticmethodcall'))
             ->back('first');
        $this->prepareQuery();

        // spotting array with array[index]
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->hasNoIn('RIGHT')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('VARIABLE', 'APPEND'))
             ->not(
                $this->side()
                     ->atomIs('Array')
                     ->outIs('INDEX')
                     ->atomIs('Integer', self::WITH_CONSTANTS)
             )
             ->atomIs(array('Array', 'Arrayappend'))
             ->back('first');
        $this->prepareQuery();

        // spotting array in a functioncall
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->hasNoIn('RIGHT')
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('NAME')
             ->atomIs('Functioncall')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
