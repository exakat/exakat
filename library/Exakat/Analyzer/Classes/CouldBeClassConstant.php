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

class CouldBeClassConstant extends Analyzer {
    public function dependsOn() {
        return array('Classes/IsModified',
                     'Classes/LocallyUnusedProperty');
    }
    
    public function analyze() {
        $this->atomIs('Ppp')
             ->hasClass()

             ->hasNoOut(array('PRIVATE', 'PROTECTED'))

             ->outIs('PPP')
             ->analyzerIsNot('Classes/LocallyUnusedProperty')

             ->hasOut('RIGHT')
             
             // Ignore null or static expressions in definitions.
             ->raw('not(where( __.out("RIGHT").hasLabel("Null", "Staticconstant") ) )')

             ->savePropertyAs('propertyname', 'name')
             ->outIs('LEFT')

             ->savePropertyAs('code', 'staticName')
             ->goToClass()

             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('METHOD')

                // usage as property with $this
             ->raw('where( __.repeat( __.out('.$this->linksDown.') ).emit( ).times('.self::MAX_LOOPING.').hasLabel("Member")
                                                .where( __.out("OBJECT").has("code", "\$this") )
                                                .where( __.out("MEMBER").filter{ it.get().value("code").toLowerCase() == name.toLowerCase() } )
                                                .where( __.in("ANALYZED").has("analyzer", "Classes/IsModified") )
                             .count().is(eq(0)) )')

                // usage as static property with (namespace, self or static)
             ->raw('where( __.repeat( __.out('.$this->linksDown.') ).emit( hasLabel("Staticproperty") ).times('.self::MAX_LOOPING.')
                                                .where( __.out("CLASS").filter{ it.get().value("fullnspath") == fnp } )
                                                .where( __.out("MEMBER").filter{ it.get().value("code").toLowerCase() == staticName.toLowerCase() } )
                                                .where( __.in("ANALYZED").has("analyzer", "Classes/IsModified") )
                             .count().is(eq(0)) )')

             ->back('first');
             
             // Exclude situations where property is used as an object or a resource (can't be class constant)
        $this->prepareQuery();
    }
}

?>
