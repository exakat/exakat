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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class WrongTypedPropertyInit extends Analyzer {
    protected $phpVersion = '7.4+';

    public function dependsOn(): array {
        return array('Complete/PropagateCalls',
                     'Complete/FollowClosureDefinition',
                     'Complete/CreateDefaultValues',
                    );
    }

    public function analyze(): void {
        // class x { a $a; function foo() { $this->a = new b()}}
        $this->atomIs('Propertydefinition')
             ->inIs('PPP')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')

             ->outIs('DEFAULT')
             ->atomIs('New')
             ->hasIn('RIGHT')
             ->outIs('NEW')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->inIs('DEFINITION')
                             ->goToAllImplements(self::INCLUDE_SELF)
                             ->samePropertyAs('fullnspath', 'fnp')
                        )
             )
             ->back('first');
        $this->prepareQuery();

        // class x { a $a; function foo() { $this->a = C::D()}}
        $this->atomIs('Propertydefinition')
             ->inIs('PPP')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')

             ->outIs('DEFAULT')
             ->atomIs(self::FUNCTIONS_CALLS)
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')

             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->atomIs(self::CIT)
                     ->goToAllImplements(self::INCLUDE_SELF)
                     ->samePropertyAs('fullnspath', 'fnp')
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
