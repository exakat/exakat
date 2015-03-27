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

class DereferencingAS extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Functioncall') // or some array-returning function
             ->hasNoIn('METHOD')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->fullnspath('\\array')
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'storage')
             ->inIs('LEFT')
             ->nextSiblings()
             ->atomInside('Variable')
             ->samePropertyAs('code', 'storage')
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->back('first')
             ->outIs('LEFT');
        $this->prepareQuery();

        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('String') // or some array-returning function
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'storage')
             ->inIs('LEFT')
             ->nextSiblings()
             ->atomInside('Variable')
             ->samePropertyAs('code', 'storage')
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->back('first')
             ->outIs('LEFT');
        $this->prepareQuery();
    }
}

?>
