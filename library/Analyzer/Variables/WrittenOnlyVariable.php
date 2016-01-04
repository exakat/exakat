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


namespace Analyzer\Variables;

use Analyzer;

class WrittenOnlyVariable extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Variables/IsModified',
                     'Variables/IsRead');
    }
    
    public function analyze() {
        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');
        
        $this->atomIs('Function')
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->codeIsNot($superglobals)
             // this variable is modified
             ->analyzerIs('Variables/IsModified')
             // this variable is not read
             ->analyzerIsNot('Variables/IsRead')

            // Another instance of this variable (based on name), in the same function, is not read
             ->filter(<<<GREMLIN
    name = it.code;
    itself = it;
    it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}.out("BLOCK").
             out().loop(1){true}{it.object.atom == "Variable"}
             .has("code", name)
             .filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsRead").any()}
             .any() == false
GREMLIN
)
;

        $this->prepareQuery();
    }
}

?>
