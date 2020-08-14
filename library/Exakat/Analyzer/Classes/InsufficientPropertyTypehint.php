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

class InsufficientPropertyTypehint extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/CreateDefaultValues',
                     'Complete/PropagateCalls',
                    );
    }

    public function analyze(): void {
        // class x { private Y $p; function foo() { $this->p->p2 = 1;} }

        // class x { function __construct(Y $p) { $this->p = $p; } function foo() { $this->p->p2 = 1;} }
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFINITION')
             ->inIs('OBJECT')
             ->atomIs('Member')
             ->hasNoIn('DEFINITION')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'property')
             ->back('first')

             ->optional(
                 $this->side()
                      ->outIs('DEFAULT')
                      ->inIs('DEFINITION')
                      ->inIs('NAME')
             )
             ->optional(
                 $this->side()
                      ->inIs('PPP')
             )
             ->outIs('TYPEHINT')
             ->inIs('DEFINITION')
             ->atomIs('Class')
             ->not(
                $this->side()
                     ->outIs('PPP')
                     ->outIs('PPP')
                     ->samePropertyAs('propertyname', 'property', self::CASE_SENSITIVE)
             )
             ->back('first');
        $this->prepareQuery();

        // class x { function __construct(Y $p) { $this->p = $p; } } interface Y {}
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFINITION')
             ->inIs('OBJECT')
             ->atomIs('Member')
             ->back('first')

             ->optional(
                 $this->side()
                      ->outIs('DEFAULT')
                      ->inIs('DEFINITION')
                      ->inIs('NAME')
             )
             ->optional(
                 $this->side()
                      ->inIs('PPP')
             )
             ->outIs('TYPEHINT')
             ->inIs('DEFINITION')
             ->atomIs('Interface')
             ->back('first');
        $this->prepareQuery();

        // class x { function __construct(Y $p) { $this->p = $p; } function foo() { $this->p->m2();} }
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFINITION')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->hasNoIn('DEFINITION')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->savePropertyAs('code', 'method')
             ->back('first')

             ->optional(
                 $this->side()
                      ->outIs('DEFAULT')
                      ->inIs('DEFINITION')
                      ->inIs('NAME')
             )
             ->optional(
                 $this->side()
                      ->inIs('PPP')
             )
             ->outIs('TYPEHINT')
             ->inIs('DEFINITION')
             ->atomIs(array('Class', 'Interface'))
             ->not(
                $this->side()
                     ->outIs('METHOD')
                     ->outIs('NAME')
                     ->samePropertyAs('code', 'method', self::CASE_INSENSITIVE)
             )
             ->back('first');
        $this->prepareQuery();

        // class x { private $p = null; function foo() { $this->p->m2();} }
        // No typehint, but used as an object
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->inIs('PPP')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first')
             ->outIs('DEFINITION')
             ->hasIn(array('OBJECT', 'CLASS'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
