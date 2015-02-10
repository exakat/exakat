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


namespace Analyzer\Security;

use Analyzer;

class RemoteInjection extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Security\\SensitiveArgument');
    }

    public function analyze() {
        // foreach 
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->raw('sideEffect{ first = it;}')

             // Must change to list of incoming points
             ->analyzerIs('Analyzer\\Security\\ContaminatedFunction')

            // Loop initialisation    .filter{ it.code == '\$a' }
             ->raw("sideEffect{ x=[]; y = it.out('ARGUMENTS').out('ARGUMENT').rank.toList(); x += [y]; x += [y] ; x += 0;}")
             ->followConnexion( 10 )
             
             // here, spot vulnerable spots
             ->raw('filter{ it.out("ARGUMENTS").out("ARGUMENT").filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Security\\\\SensitiveArgument").any() }.any()}')
             ->raw('transform{ first ; }');
        $this->prepareQuery();
    }
}

// handle functions : OK (Test 1,2);
// Handle methods 
// Handle staticmethods
// handle multiple arguments : OK (arguments 2,3,4)
// handle non relayed arguments : OK (arugments 2,3,4)
// handle default values, references and typehint 
// check on retro-feedback (function calling back another function already called : limiter by Iternation number, or by finding something early. Otherwise, good reaction.
// check on drive-by functions call (the one not at the end of the path)

// had a check on final methods that are sensitive to check  (OK).


?>
