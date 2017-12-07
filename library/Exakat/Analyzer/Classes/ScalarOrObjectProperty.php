<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
             ->outIs('RIGHT')
             ->atomIs($literals)
             ->back('first')
             ->outIs('METHOD')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Member')
             ->outIs('MEMBER')
             ->samePropertyAs('code', 'name')
             ->inIs('MEMBER')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->inIs('OBJECT') // Good for methodcall and properties
             ->back('results');
        $this->prepareQuery();

        // Property defined as object, assigned also literal
        $this->atomIs('Class')
             ->outIs('PPP')
             ->outIs('PPP')
             ->_as('results')
             ->savePropertyAs('propertyname', 'name')
             ->raw('or( __.out("RIGHT").hasLabel("Null"), __.not(out("RIGHT")) )')
             ->back('first')
             
             ->findAssignation('New')
             ->findAssignation($literals)
             
             ->back('results');
        $this->prepareQuery();
    }
    
    private function findAssignation($atoms) {
        $this->outIs('METHOD')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Member')
             ->outIs('MEMBER')
             ->samePropertyAs('code', 'name')
             ->inIs('MEMBER')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs($atoms)
             ->back('first');

        return $this;
    }
}

?>
