<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Structures;

use Analyzer;

class CouldBeStatic extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Global')
             ->hasFunction()
             ->outIs('GLOBAL')
             ->savePropertyAs('code', 'theGlobal')
             ->goToFunction()
             ->outIs('NAME')
             ->savePropertyAs('code', 'theFunction')

             // this variable is both in the current function and another
             ->filter('g.idx("atoms")[["atom":"Global"]].out("GLOBAL").has("code", theGlobal)
                        .in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}
                           .filter{ it.out("NAME").has("code", theFunction).any() == false}
                        .any() == false')

             // this variable is both in the current function and the global space
             ->filter('g.idx("atoms")[["atom":"Global"]].out("GLOBAL").has("code", theGlobal)
                        .in.loop(1){!(it.object.atom in ["Function", "File"])}{it.object.atom == "File"}
                        .any() == false')

             // this variable is both in the current function and another via $GLOBALS
             ->filter('g.idx("atoms")[["atom":"Array"]].filter{ it.out("VARIABLE").has("code", "\$GLOBALS").any()}
                         .out("INDEX").filter{ "\$" + it.noDelimiter == theGlobal}.any() == false')
            // todo : add check on function to avoid itself.
             ->back('first');
        $this->prepareQuery();
        
        // todo : add support for the $GLOBALS 
    }
}

?>
