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


namespace Analyzer\Classes;

use Analyzer;

class DefinedProperty extends Analyzer\Analyzer {

    public function analyze() {
        // locally defined
        $this->atomIs("Property")
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'property')
             ->makeVariable('property')
             ->goToClass()
             ->outIs('BLOCK')
             ->atomInside('Ppp')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

        // defined in parents (Extended)
        $this->atomIs("Property")
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'property')
             ->makeVariable('property')
             ->goToClass()
             ->goToExtends()
             ->outIs('BLOCK')
             ->atomInside('Ppp')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

        // defined in parents implemented
        $this->atomIs("Property")
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'property')
             ->makeVariable('property')
             ->goToClass()
             ->goToImplements()
             ->outIs('BLOCK')
             ->atomInside('Ppp')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

        // defined in traits (use)
        $this->atomIs("Property")
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'property')
             ->makeVariable('property')
             ->goToClass()
             ->goToTraits()
             ->outIs('BLOCK')
             ->atomInside('Ppp')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'property')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
