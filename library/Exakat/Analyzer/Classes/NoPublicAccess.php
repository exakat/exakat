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

class NoPublicAccess extends Analyzer {
    public function analyze() {

        $gremlin = <<<GREMLIN
g.V().hasLabel("Member")
     .not(where( __.out("OBJECT").has("code", "\$this")) )
     .out("MEMBER").hasLabel("Name")
     .values('code').unique();
GREMLIN;
        $properties = $this->query($gremlin);

        if(!empty($properties)) {
            $properties = array_values($properties);
            $this->atomIs('Ppp')
                 ->hasOut('PUBLIC')
                 ->hasNoOut('STATIC')
                 ->outIs('PPP')
                 ->_as('ppp')
                 ->isNot('propertyname', $properties)
                 ->back('ppp');
            $this->prepareQuery();
        }

        $gremlin = <<<GREMLIN
g.V().hasLabel("Staticproperty")
     .where( 
     __.out("CLASS")
         .has("token", within("T_STRING", "T_NS_SEPARATOR"))
         .not(has("code", within(["self", "static"])))
         .sideEffect{fnp = it.get().value("fullnspath");}
     .in("CLASS")
     .out("MEMBER").hasLabel("Variable")
     .map{ full = fnp + '::' + it.get().value("code"); }
     )
     .map{ full; }
     .unique();
GREMLIN;
        $staticproperties = $this->query($gremlin);
        
        if (!empty($staticproperties)) {
            $staticproperties = array_values($staticproperties);
            $this->atomIs('Ppp')
                 ->hasOut('PUBLIC')
                 ->hasOut('STATIC')
                 ->inIs('PPP')
                 ->savePropertyAs('fullnspath', 'fnp')
                 ->back('first')
                 ->outIs('PPP')
                 ->_as('results')
                 ->outIsIE('LEFT')
                 ->raw('filter{ !(fnp + "::" + it.get().value("code") in ***) }', $staticproperties)
                 ->back('results');
            $this->prepareQuery();
        }
    }
}

?>
