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

class NoPublicAccess extends Analyzer {
    public function analyze() {

        $queryProperties = <<<GREMLIN
g.V().hasLabel("Member")
     .not(where( __.out("OBJECT").hasLabel("This")) )
     .out("MEMBER").hasLabel("Name")
     .values('code').unique();
GREMLIN;
        $properties = $this->query($queryProperties)->toArray();

        if(!empty($properties)) {
            $properties = array_values($properties);
            $this->atomIs('Ppp')
                 ->is('visibility', 'public')
                 ->isNot('static', true)
                 ->outIs('PPP')
                 ->_as('ppp')
                 ->isNot('propertyname', $properties)
                 ->back('ppp');
            $this->prepareQuery();
        }

        $queryStaticProperties = <<<GREMLIN
g.V().hasLabel("Staticproperty")
     .where( 
     __.out("CLASS")
         .has("token", within("T_STRING", "T_NS_SEPARATOR"))
         .not(hasLabel("Self", "Static"))
         .sideEffect{fnp = it.get().value("fullnspath");}
     .in("CLASS")
     .out("MEMBER").hasLabel("Staticpropertyname")
     .map{ full = fnp + '::' + it.get().value("code"); }
     )
     .map{ full; }
     .unique();
GREMLIN;
        $staticproperties = $this->query($queryStaticProperties)
                                 ->toArray();
        
        if (!empty($staticproperties)) {
            $staticproperties = array_values($staticproperties);
            $this->atomIs('Ppp')
                 ->is('visibility', 'public')
                 ->is('static', true)
                 ->inIs('PPP')
                 ->savePropertyAs('fullnspath', 'fnp')
                 ->back('first')
                 ->outIs('PPP')
                 ->_as('results')
                 ->raw('filter{ !(fnp + "::" + it.get().value("code") in ***) }', $staticproperties)
                 ->back('results');
            $this->prepareQuery();
        }
    }
}

?>
