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


namespace Analyzer\Functions;

use Analyzer;

class UnusedArguments extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Variables/Arguments',
                     'Variables/IsRead',
                     'Variables/IsModified',
                     );
    }
    
    public function analyze() {
        // Arguments, not reference
        $this->analyzerIs('Variables/Arguments')
             ->savePropertyAs('code', 'varname')
             ->isNot('reference', true)
             ->inIsIE('LEFT')     // for default value
             ->inIsIE('VARIABLE') // for typehint
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->hasNoOut('ABSTRACT')
             ->notInTrait()
             ->notInInterface()
             
             // this argument must be read at least once
             ->filter('it.out("BLOCK").out.loop(1){true}{ it.object.atom == "Variable" }.has("code", varname).filter{ it.in("ANALYZED").has("code", "Variables/IsRead").any()}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // Arguments, reference
        $this->analyzerIs('Variables/Arguments')
             ->savePropertyAs('code', 'varname')
             ->is('reference', true)
             ->inIsIE('LEFT')     // for default value
             ->inIsIE('VARIABLE') // for typehint
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->hasNoOut('ABSTRACT')
             ->notInTrait()
             ->notInInterface()
             
             // this argument must be read or written at least once
             ->filter('it.out("BLOCK").out.loop(1){true}{ it.object.atom == "Variable" }.has("code", varname)
                                          .filter{ it.in("ANALYZED").filter{ it.code in ["Variables/IsModified", "Variables/IsRead"]}.any()}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // Arguments in a USE, not a reference
        $this->atomIs('Function')
             ->is('lambda', true)
             ->outIs('USE')
             ->outIs('ARGUMENT')
             ->isNot('reference', true)
             ->savePropertyAs('code', 'varname')
             ->_as('first')
             ->inIs('ARGUMENT')
             ->inIs('USE')
             
             // this argument must be read or written at least once
             ->filter('it.out("BLOCK").out.loop(1){true}{ it.object.atom == "Variable" }.has("code", varname).filter{ it.in("ANALYZED").has("code", "Variables/IsRead").any()}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // Arguments in a USE, reference
        $this->atomIs('Function')
             ->is('lambda', true)
             ->outIs('USE')
             ->outIs('ARGUMENT')
             ->is('reference', true)
             ->savePropertyAs('code', 'varname')
             ->_as('result')
             ->inIs('ARGUMENT')
             ->inIs('USE')
             
             // this argument must be read or written at least once
             ->filter('it.out("BLOCK").out.loop(1){true}{ it.object.atom == "Variable" }.has("code", varname)
                                          .filter{ it.in("ANALYZED").filter{ it.code in ["Variables/IsModified", "Variables/IsRead"]}.any()}.any() == false')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
