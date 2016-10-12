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
use Exakat\Tokenizer\Token;

class CouldBePrivate extends Analyzer\Analyzer {
    public function analyze() {
        $linksDown = Token::linksAsList();
        // Searching for properties that are never used outside the definition class or its children

        // Non-static properties
        $this->atomIs('Ppp')
             ->hasClass()
             ->hasNoOut('PRIVATE')
             ->hasNoOut('STATIC')

             ->goToClassTrait()
             ->hasName()
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')

             ->outIs('PPP')
             ->_as('results')

            // Skip properties with 'null' as default : they will probably get an object, and can't be unused.
             ->savePropertyAs('propertyname', 'name')
             
             // property is never used, outside the current class, in a children class
             ->raw('where( g.V().hasLabel("Property").where( __.out("PROPERTY").filter{ it.get().value("code") == name})
                                                      // Object is not inside the current and parent class
                                                     .where( __.out("OBJECT").has("code", "\$this") )
                                                     .not(or(__.until( hasLabel("Class").where(__.out("NAME").hasLabel("Void").is(eq(0))) ).repeat( __.in('.$linksDown.')).filter{ it.get().value("fullnspath") != fnp }.count().is(neq(0)),
                                                         __.out("OBJECT").has("code", "\$this").count().is(eq(0))
                                                         ))
                                                     .count().is(neq(0)) 
                          )')
             ->back('results');
        $this->prepareQuery();

        // Static properties
        $this->atomIs('Ppp')
             ->hasClassTrait()
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

            // The static property is not used inside the defining class, nor its children
             ->raw('where( g.V().hasLabel("Staticproperty").where( __.out("PROPERTY").filter{ it.get().value("code") == dname})
                                                           .where( __.out("CLASS").has("token", within("T_STRING", "T_NS_SEPARATOR", "T_STATIC")).filter{ it.get().value("fullnspath") == fnp})

                                                            // Not in the defining class
                                                           .where( __.until( hasLabel("Class", "File") ).emit(hasLabel("Class", "File")).repeat( __.in('.$linksDown.')).filter{ it.get().label() == "File" || it.get().value("fullnspath") != fnp }.count().is(eq(0)) ) 

                                                           .count().is(neq(0))
                          )')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
