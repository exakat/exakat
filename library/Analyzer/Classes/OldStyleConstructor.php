<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class OldStyleConstructor extends Analyzer\Analyzer {
    public function analyze() {
        $hasNo__construct = 'where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{ it.get().value("code").toLowerCase() == "__construct"}.count().is(eq(0)) )';

        $this->atomIs('Class')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->raw($hasNo__construct)
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->goToNamespace()
             ->atomIs('File') // no namespace => Global
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Class')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->raw($hasNo__construct)
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->goToNamespace()
             ->atomIs('Namespace')
             ->outIs('NAME')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
