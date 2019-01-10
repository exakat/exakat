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

namespace Exakat\Analyzer\Traits;

use Exakat\Analyzer\Analyzer;

class DependantTrait extends Analyzer {
    public function analyze() {
        // Case for $this->method()
        $this->atomIs('Trait')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Methodcall')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->hasNoIn('DEFINITION')
             ->back('first');
        $this->prepareQuery();

        // Case for $this->$properties
        $this->atomIs('Trait')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Member')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->hasNoIn('DEFINITION')
             ->back('first');
        $this->prepareQuery();

        // Case for class::$properties
        $this->atomIs('Trait')
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Staticproperty')
             ->outIs('CLASS')
             ->has('fullnspath')
             ->samePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->hasNoIn('DEFINITION')
             ->back('first');
        $this->prepareQuery();

        // Case for class::methodcall
        $this->atomIs('Trait')
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->samePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->hasNoIn('DEFINITION')
             ->back('first');
        $this->prepareQuery();

        // Case for class::methodcall

        // Case for class::constant
        // self will be solved at excution time, but is set to the trait statically
        $this->atomIs('Trait')
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Staticconstant')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
