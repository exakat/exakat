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

class UnitializedProperties extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor',
                     'Classes/IsModified');
    }
    
    public function analyze() {
        // Normal Properties
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->hasNoOut('STATIC')
             ->outIs('PPP')
             ->atomIsNot('Assignation')
             ->_as('results')
             ->savePropertyAs('propertyname', 'property')
             ->back('first')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Method')
             ->analyzerIs('Classes/Constructor')
             ->raw('where(
    __.out("BLOCK").emit( hasLabel("Property")).repeat( out() ).times('.self::MAX_LOOPING.')
      .hasLabel("Property").where(__.out("PROPERTY").has("token", "T_STRING").filter{ it.get().value("code") == property})
      .where( __.in("ANALYZED").has("analyzer", "Classes/IsModified").count().is(eq(1)) ).count().is(eq(0))
)')
             ->back('results');
        $this->prepareQuery();

        // Static Properties
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'classe')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->hasOut('STATIC')
             ->outIs('PPP')
             ->atomIsNot('Assignation')
             ->_as('results')
             ->savePropertyAs('code', 'property')
             ->back('first')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Method')
             ->analyzerIs('Classes/Constructor')
             ->raw('where(
    __.out("BLOCK").emit( hasLabel("Staticproperty")).repeat( out() ).times('.self::MAX_LOOPING.')
      .hasLabel("Staticproperty").out("CLASS").filter{ it.get().value("fullnspath") == classe}.in("CLASS").where(__.out("PROPERTY").filter{ it.get().value("code") == property})
      .where( __.in("ANALYZED").has("analyzer", "Classes/IsModified").count().is(eq(1)) ).count().is(eq(0))
)')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
