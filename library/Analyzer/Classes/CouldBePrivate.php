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

class CouldBePrivate extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Classes/LocallyUnusedProperty');
    }
    
    public function analyze() {

        // Searching for properties that are never used outside the definition class
        $this->atomIs('Visibility')
             ->hasOut('PUBLIC')

             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')

             ->outIs('DEFINE')
             ->_as('results')
             ->analyzerIsNot('Classes/LocallyUnusedProperty')

             ->filter('it.out("RIGHT").filter{it.atom in ["Null", "Staticconstant"]}.any() == false')
             ->savePropertyAs('propertyname', 'name')
             ->outIsIE('RIGHT')

                // property is never used, outside with $this 
             ->filter('g.idx("atoms")[["atom":"Property"]].filter{ it.out("PROPERTY").filter{ it.code.toLowerCase() == name.toLowerCase()}.any() }
                                                          .filter{ it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class" && it.object.fullnspath == fnp}.any() == false || 
                                                                   it.out("OBJECT").has("atom", "Variable").has("code", "\$this").any() == false}
                                                          .any() == false')

                // property is never statically used, except with 'self', 'static'
             ->filter('g.idx("atoms")[["atom":"Staticproperty"]].filter{  it.out("CLASS").has("fullnspath", fnp).any() }
                                                                .filter{ it.out("PROPERTY").has("atom", "Variable").filter{ it.code.toLowerCase() == "\$" + name.toLowerCase()}.any()}
                                                                .filter{ it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class" && it.object.fullnspath == fnp}.any() == false }
                                                                .any() == false')
             ->back('results');
             
             // Exclude situations where property is used as an object or a resource (can't be class constant)
        $this->prepareQuery();


        // Searching for properties that are never used outside the definition class or its children
        $this->atomIs('Visibility')
             ->hasOut('PROTECTED')

             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')

             ->outIs('DEFINE')
             ->_as('results')
             ->analyzerIsNot('Classes/LocallyUnusedProperty')

             ->filter('it.out("RIGHT").filter{it.atom in ["Null", "Staticconstant"]}.any() == false')
             ->savePropertyAs('propertyname', 'name')
             ->outIsIE('RIGHT')

                // property is never used, outside the current class, in a children class
             ->filter('g.idx("atoms")[["atom":"Property"]].filter{ it.out("PROPERTY").filter{ it.code.toLowerCase() == name.toLowerCase()}.any() }
                                                          .filter{ it.in.loop(1){it.object.atom != "Class"}{(it.object.atom == "Class") && 
                                                                                                            (it.object.fullnspath != fnp) &&
                                                                                                            (fnp in it.object.classTree)
                                                                                                            }.any()}
                                                          .any() == false')

                // property is never statically used, except with 'self', 'static'
             ->filter('g.idx("atoms")[["atom":"Staticproperty"]].filter{ it.out("PROPERTY").filter{ it.code.toLowerCase() == "\\$" + name.toLowerCase()}.any() }
                                                                .filter{ it.in.loop(1){it.object.atom != "Class"}{(it.object.atom == "Class") && 
                                                                                                                  (it.object.fullnspath != fnp) &&
                                                                                                                  (fnp in it.object.classTree)
                                                                                                                  }.any()}
                                                          .any() == false')

             ->back('results');
             
             // Exclude situations where property is used as an object or a resource (can't be class constant)
        $this->prepareQuery();
    }
}

?>
