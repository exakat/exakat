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
use Exakat\Tokenizer\Token;

class CouldBePrivate extends Analyzer {
    public function analyze() {
        // Searching for properties that are never used outside the definition class or its children

        // Non-static properties
        // Case of property->property (that's another public access)
        $publicProperties = $this->query('g.V().hasLabel("Property")
                                              .where( __.out("OBJECT").not(has("code", "\$this")) )
                                              .out("PROPERTY")
                                              .hasLabel("Identifier")
                                              .values("code").unique()');

        $protectedProperties = $this->query('g.V().hasLabel("Property")
                                              .where( __.out("OBJECT").has("code", "\$this") )
                                              .out("PROPERTY")
                                              .hasLabel("Identifier")
                                              .values("code").unique()');

        $this->atomIs('Ppp')
             ->hasNoOut('PRIVATE')
             ->hasNoOut('STATIC')
             ->isNot('', $publicProperties)
             ->isNot('', $protectedProperties);
        $this->prepareQuery();

        // Static properties
        // Case of property::property (that's another public access)
        $publicStaticProperties = $this->query('g.V().hasLabel("Staticproperty")
                                                     .out("CLASS")
                                                     .not(has("code", within("self", "static")))
                                                     .as("classe")
                                                     .sideEffect{ fns = it.get().value("fullnspath"); }
                                                     .in("CLASS")
                                                     .out("PROPERTY")
                                                     .hasLabel("Variable")
                                                     .as("property")
                                                     .repeat( __.in('.$this->linksDown.')).until(hasLabel("Class", "File") )
                                                     .coalesce( hasLabel("File"), filter{it.get().value("fullnspath") != fns; })
                                                     .select("classe", "property").by("fullnspath").by("code")
                                                     .unique()');
        if (empty($publicStaticProperties)) { return; }

        $calls = array();
        foreach($publicStaticProperties as $value) {
            if (isset($calls[$value->property])) {
                $calls[$value->property][] = $value->classe;
            } else {
                $calls[$value->property] = array($value->classe);
            }
        }
        
        // Property that is not used outside this class or its children
        $this->atomIs('Ppp')
             ->hasNoOut('PRIVATE')
             ->hasOut('STATIC')
             ->outIs('PPP')
             ->isNot('code', array_keys($calls));
        $this->prepareQuery();
    }
}

?>
