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

class ScalarOrObjectProperty extends Analyzer {
    public function analyze() {
        $literals = array('Integer', 'String', 'Real');

        // Property defined as literal, used as object
        $this->atomIs('Class')
             ->outIs('PPP')
             ->outIs('PPP')
             ->_as('results')
             ->savePropertyAs('propertyname', 'name')
             ->outIs('DEFAULT')
             ->isLiteral()
             ->atomIsNot('Null')
             ->inIs('DEFAULT')
             ->outIs('DEFINITION')
             ->inIs('OBJECT') // Good for methodcall and properties
             ->back('results');
        $this->prepareQuery();

        // Property defined as object, assigned as literal
        $this->atomIs('Class')
             ->outIs('PPP')
             ->outIs('PPP')
             ->_as('results')
             ->savePropertyAs('propertyname', 'name')
             ->raw('or( __.out("DEFAULT").hasLabel("Null"), __.not(out("DEFAULT")) )')

             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('New')
             
             ->back('results')
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs($literals)

             ->back('results');
        $this->prepareQuery();
    }
}

?>
