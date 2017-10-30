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


namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class OverwrittenLiterals extends Analyzer {
    public function dependsOn() {
        return array('Variables/IsModified',
                    );
    }
    
    public function analyze() {
        $assignations = $this->query(<<<GREMLIN
g.V().hasLabel("Assignation").has("code", "=")
     .where( __.in("EXPRESSION").in("INIT").count().is(eq(0)) )
     .where( __.in("PPP").count().is(eq(0)) )
     .out("RIGHT").hasLabel("Integer", "String", "Real", "Null", "Boolean").in("RIGHT")
     .out("LEFT").hasLabel("Variable", "Array", "Member", "Staticproperty")
     .groupCount("m").by("fullcode").cap("m").next().findAll{ it.value > 1; }.keySet()
GREMLIN
        )->toArray();

        $this->atomIs('Assignation')
             ->codeIs('=')
             ->raw('where( __.in("EXPRESSION").in("INIT").count().is(eq(0)) )')
             ->raw('where( __.in("PPP").count().is(eq(0)) )')
             ->outIs('RIGHT')
             ->atomIs(array('Integer', 'String', 'Real', 'Null', 'Boolean'))
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->codeIs($assignations);
        $this->prepareQuery();
    }
}

?>
