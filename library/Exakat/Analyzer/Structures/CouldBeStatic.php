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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class CouldBeStatic extends Analyzer {
    public function dependsOn() {
        return array('Structures/GlobalInGlobal');
    }
    
    public function analyze() {

        $this->atomIs('Globaldefinition')
             ->savePropertyAs('code', 'theGlobal')
             ->hasFunction()
             ->goToFunction()

             ->outIs('NAME')
             ->savePropertyAs('code', 'theFunction')
             
             // This global is only in the current function
             ->raw('not( where( g.V().hasLabel("Globaldefinition").filter{ it.get().value("code") == theGlobal }
                             .repeat(__.in()).until(and(hasLabel("Function"), where(__.out("NAME").not(hasLabel("Void")) )))
                             .out("NAME").filter{ it.get().value("code") != theFunction }
                             ) )')

             // This global is only in the current function
             ->raw('not( where( g.V().hasLabel("Array").has("globalvar").filter{ it.get().value("globalvar") == theGlobal } ) )')

             // This global is only in the current function
             ->raw('not( where( g.V().hasLabel("Variable", "Globaldefinition").filter{ it.get().value("code") == theGlobal }
                             .where( __.in("ANALYZED").has("analyzer", "Structures/GlobalInGlobal") ) )
                             )')
             ->back('first')
             ->inIs('GLOBAL');
        $this->prepareQuery();
    }
}

?>
