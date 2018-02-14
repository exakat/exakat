<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class WrittenOnlyVariable extends Analyzer {
    
    public function dependsOn() {
        return array('Variables/IsModified',
                     'Variables/IsRead');
    }
    
    public function analyze() {
        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');
        
        $this->atomIs('Function')
             ->outIs('BLOCK')
             ->atomInside(self::$VARIABLES_ALL)
             ->_as('results')
             ->codeIsNot($superglobals)
             // this variable is modified
             ->analyzerIs('Variables/IsModified')
             // this variable is not read
             ->analyzerIsNot('Variables/IsRead')
             ->raw('sideEffect{ name = it.get().value("code"); }')
             
             ->goToFunction()
             ->raw('where( __.out("BLOCK").repeat( __.out('.$this->linksDown.')).emit(hasLabel("Variable", "Variableobject", "Variablearray")).times('.self::MAX_LOOPING.')
                             .filter{ it.get().value("code") == name}
                             .where( __.in("ANALYZED").has("analyzer", "Variables/IsRead").count().is(neq(0)) )
                             .count().is(eq(0)) )')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
