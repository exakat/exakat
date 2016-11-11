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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class OneDotOrObjectOperatorPerLine extends Analyzer {
    public function analyze() {
        // Two expressions in a row
        $this->atomIs(array('Property', 'Methodcall'))
             ->hasNoIn('OBJECT')
             ->savePropertyAs('line', 'line')
             ->outIs('OBJECT')
             ->atomIs(array('Property', 'Methodcall'))
             ->samePropertyAs('line', 'line')
             ->back('first')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // Two expressions with HTML between
        $this->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->savePropertyAs('line', 'line')
             ->nextSibling('CONCAT')
             ->samePropertyAs('line', 'line')
             ->nextSibling('CONCAT')
             ->samePropertyAs('line', 'line')
             ->back('first')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // f('a'.'b', $c->d);
        $this->atomIs('Concatenation')
             ->savePropertyAs('line', 'line')
             ->nextSibling('ARGUMENT')
             ->atomIs(array('Concatenation', 'Methodcall', 'Property'))
             ->samePropertyAs('line', 'line')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->analyzerIsNot('self');
        $this->prepareQuery();
    }
}

?>
