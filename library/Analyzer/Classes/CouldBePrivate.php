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
    public function analyze() {
        $linksDown = \Tokenizer\Token::linksAsList();
        // Searching for properties that are never used outside the definition class or its children

        // Non-static properties
        $this->atomIs('Ppp')
             ->hasNoOut('PRIVATE')
             ->hasNoOut('STATIC')

             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')

             ->outIs('PPP')
             ->_as('results')

            // Skip properties with 'null' as default : they will probably get an object, and can't be unused.
             ->outIsIE('LEFT')
             ->savePropertyAs('propertyname', 'name')
             
             // property is never used, outside the current class, in a children class
             ->raw('where( g.V().hasLabel("Property").where( __.out("PROPERTY").filter{ it.get().value("code") == name})
                                                      // Object is not inside the current and parent class
                                                     .where( __.out("OBJECT").has("code", "\$this") )
                                                     .not(or(__.until( hasLabel("Class") ).repeat( __.in('.$linksDown.')).filter{ it.get().value("fullnspath") != fnp }.count().is(neq(0)),
                                                         __.out("OBJECT").has("code", "\$this").count().is(eq(0))
                                                         ))
                                                     .count().is(neq(0)) 
                          )')
             ->back('results');
        $this->prepareQuery();

//                                                     .where( __.until( hasLabel("Class") ).repeat( __.in('.$linksDown.')).as("a").until(__.out("EXTENDS").in("DEFINITION").count().is(eq(0)) ).emit().repeat( out("EXTENDS").in("DEFINITION") ).filter{ it.get().value("fullnspath") == fnp }.count().is(eq(0)) ) 

        // Static properties
        $this->atomIs('Ppp')
             ->hasNoOut('PRIVATE')
             ->hasOut('STATIC')

             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')

             ->outIs('PPP')
             ->_as('results')

            // Skip properties with 'null' as default : they will probably get an object, and can't be unused.
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'dname')

             ->raw('where( g.V().hasLabel("Staticproperty").where( __.out("PROPERTY").filter{ it.get().value("code") == dname})
                                                           .where( __.out("CLASS").filter{ it.get().value("fullnspath") == fnp})

                                                            // Not in the defining class
                                                           .where( __.until( hasLabel("Class", "File") ).repeat( __.in('.$linksDown.')).filter{ it.get().label() == "File" || it.get().value("fullnspath") != fnp }.count().is(eq(0)) ) 

                                                           .count().is(neq(0))
                          )')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
