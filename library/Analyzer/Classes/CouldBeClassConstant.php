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

class CouldBeClassConstant extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Classes/IsModified',
                     'Classes/LocallyUnusedProperty');
    }
    
    public function analyze() {
        $this->atomIs('Visibility')

             ->hasNoOut(array('PRIVATE', 'PROTECTED'))

             ->outIs('DEFINE')
             ->analyzerIsNot('Classes/LocallyUnusedProperty')

             ->hasOut('RIGHT')
             ->filter('it.out("RIGHT").filter{it.atom in ["Null", "Staticconstant"]}.any() == false')

             ->savePropertyAs('propertyname', 'name')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'staticName')
             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('BLOCK')

                // usage as property with $this
             ->raw('filter{it.out.loop(1){true}{it.object.atom == "Property"}.filter{ it.out("OBJECT").has("code", "\$this").any()}
                                                                             .filter{ it.out("PROPERTY").filter{ it.code.toLowerCase() == name}.any()}
                                                                             .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\IsModified").any()}
                                                                             .any() == false}')

                // usage as static property with (namespace, self or static)
             ->raw('filter{it.out.loop(1){true}{it.object.atom == "Staticproperty"}.filter{ it.out("CLASS").filter{ it.fullnspath == fnp}.any()}
                                                                                   .filter{ it.out("PROPERTY").filter{ it.code.toLowerCase() == staticName.toLowerCase()}.any() || it.out("PROPERTY").out("VARIABLE").filter{ it.code.toLowerCase() == staticName.toLowerCase()}.any()}
                                                                                   .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\IsModified").any()}
                                                                                   .any() == false}')
             ->back('first');
             
             // Exclude situations where property is used as an object or a resource (can't be class constant)
        $this->prepareQuery();
    }
}

?>
