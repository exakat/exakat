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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;
use Exakat\Query\DSL\FollowParAs;

class MismatchTypeAndDefault extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                     'Complete/PropagateCalls',
                     'Complete/MakeClassConstantDefinition',
                    );
    }

    public function analyze() {
        // function foo(string $s = 3)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('arg')
             ->outIs('DEFAULT')
             ->atomIsNot('Void')
             ->hasNoIn('RIGHT')
             ->followParAs(FollowParAs::FOLLOW_NONE) // basic handling of ternary
             ->atomIs(self::TYPE_ATOMS, self::WITH_CONSTANTS)
             // In case we stay here, even after following the constants
             ->atomIsNot(self::STATIC_NAMES)
             ->savePropertyAs('label', 'type')
             ->back('arg')
             ->isNotNullable()
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->notCompatibleWithType('type')
             ->back('first');
        $this->prepareQuery();

        // function foo(?string $s = 3)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('arg')
             ->outIs('DEFAULT')
             ->atomIsNot('Void')
             ->hasNoIn('RIGHT')
             ->followParAs(FollowParAs::FOLLOW_NONE) // basic handling of ternary
             ->atomIs(self::TYPE_ATOMS, self::WITH_CONSTANTS)
             // In case we stay here, even after following the constants
             ->atomIsNot(self::STATIC_NAMES)
             ->savePropertyAs('label', 'type')
             ->back('arg')
             ->isNullable()
             ->outIs('TYPEHINT')
             ->atomIsNot('Null')
             ->notCompatibleWithType('type')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
