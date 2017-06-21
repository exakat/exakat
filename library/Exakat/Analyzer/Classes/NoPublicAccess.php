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
g.V().hasLabel("Member").out("OBJECT").not(has("code", "\$this")).in("OBJECT")
     .out("MEMBER").hasLabel("Identifier").map{ it.get().value("code"); }.unique();
GREMLIN;
        $properties = $this->query($gremlin);
        
        $this->atomIs('Ppp')
             ->hasOut('PUBLIC')
             ->hasNoOut('STATIC')
             ->outIs('PPP')
             ->_as('ppp')
             ->isNot('propertyname', $properties)
             ->back('ppp');
        $this->prepareQuery();

        $gremlin = <<<GREMLIN
g.V().hasLabel("Staticproperty").out("CLASS").has("token", within("T_STRING", "T_NS_SEPARATOR")).not(has("code", within(["self", "static"]))).sideEffect{fnp = it.get().value("fullnspath");}.in("CLASS")
     .out("MEMBER").hasLabel("Variable").map{ fnp + '::' + it.get().value("code"); }.unique();
GREMLIN;
        $staticproperties = $this->query($gremlin);
        
        if (count($staticproperties) > 0) {
            $this->atomIs('Ppp')
                 ->hasOut('PUBLIC')
                 ->hasOut('STATIC')
                 ->outIs('PPP')
                 ->_as('results')
                 ->outIsIE('LEFT')
                 ->_as('ppp')
                 ->goToClass()
                 ->savePropertyAs('fullnspath', 'fnp')
                 ->back('ppp')
                 ->raw('filter{ !(fnp + "::" + it.get().value("code") in ['.str_replace('$', '\\$', str_replace("\\", "\\\\", makeList($staticproperties))).'] )}')
                 ->back('results');
            $this->prepareQuery();
        }
    }
}

?>
