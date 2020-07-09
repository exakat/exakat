<?php declare(strict_types = 1);
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

namespace Exakat\Analyzer\Complete;

class FollowClosureDefinition extends Complete {
    public function dependsOn() : array {
        return array('Complete/CreateDefaultValues',
                    );
    }

    public function analyze() {
        // immediate usage : in parenthesis
        $this->atomIs(array('Closure', 'Arrowfunction'), self::WITHOUT_CONSTANTS)
             ->inIsIE('RIGHT') // Skip all $closure =
             ->inIs('CODE')
             ->atomIs('Parenthesis')
             ->inIs('NAME')
             ->atomIs('Functioncall')
             ->hasNoIn('DEFINITION')
             ->addETo('DEFINITION', 'first');
        $this->prepareQuery();

        // local usage
        $this->atomIs(array('Closure', 'Arrowfunction'), self::WITHOUT_CONSTANTS)
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->inIs('DEFINITION')  // Find all variable usage
             ->outIs('DEFINITION')
             ->inIs('NAME')
             ->atomIs('Functioncall', self::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
             ->addEFrom('DEFINITION', 'first');
        $this->prepareQuery();

        // relayed usage foo(function(){}); function foo($a) { $a();}
        $this->atomIs(array('Closure', 'Arrowfunction'), self::WITH_VARIABLES)
             ->hasIn('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->inIs('DEFINITION')  // Find all variable usage
             ->outToParameter('ranked')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('NAME')
             ->atomIs('Functioncall', self::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
             ->addEFrom('DEFINITION', 'first');
        $this->prepareQuery();

        // relayed usage $d = function(){}; foo($d); function foo($a) { $a();}
        $this->atomIs(array('Closure', 'Arrowfunction'), self::WITH_VARIABLES)
             ->inIs('DEFAULT')
             ->outIs('DEFINITION')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->inIs('DEFINITION')  // Find all variable usage
             ->outToParameter('ranked')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('NAME')
             ->atomIs('Functioncall', self::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
             ->addEFrom('DEFINITION', 'first');
        $this->prepareQuery();
    }
}

?>
