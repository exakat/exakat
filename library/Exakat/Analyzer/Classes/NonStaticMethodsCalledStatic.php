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

class NonStaticMethodsCalledStatic extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/SetClassMethodRemoteDefinition',
                     'Complete/SetArrayClassDefinition',
                    );
    }

    public function analyze(): void {
        // check outside the class : the first found class has not method
        // Here, we find methods that are in the grand parents, and not static.

        // the method is outside a class
        $this->atomIs('Staticmethodcall')
             ->hasNoClassTrait()

             ->inIs('DEFINITION')
             ->isNot('static', true)
             ->back('first');
        $this->prepareQuery();

        // the method is defined in a child class
        $this->atomIs('Staticmethodcall')
             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnqOrigin')
             ->back('first')

             ->outIs('CLASS')
             ->atomIsNot(self::RELATIVE_CLASS)

             // The child must be below the parent, then it is an external class
             ->filter(
                $this->side()
                     ->inIs('DEFINITION')
                     ->goToAllParents(self::EXCLUDE_SELF)
                     ->samePropertyAs('fullnspath', 'fnqOrigin')
             )
             ->back('first')

             ->inIs('DEFINITION')
             ->isNot('static', true)
             ->back('first');
        $this->prepareQuery();

        // the method is defined in a parent class or self
        $this->atomIs('Staticmethodcall')
             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnqOrigin')
             ->back('first')

             ->outIs('CLASS')
             ->atomIsNot(self::RELATIVE_CLASS)

             // The child must be below the parent, then it is an external class
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->goToAllParents(self::INCLUDE_SELF)
                     ->samePropertyAs('fullnspath', 'fnqOrigin')
             )
             ->back('first')

             ->inIs('DEFINITION')
             ->isNot('static', true)

             ->inIs(array('METHOD', 'MAGICMETHOD'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->not(
                $this->side()
                     ->goToClass()
                     ->goToAllParents(self::EXCLUDE_SELF)
                     ->samePropertyAs('fullnspath', 'fnp')
             );
        $this->prepareQuery();

        // ['a', 'm']() ; class a { function m() {}}
        $this->atomIs('Functioncall')
             ->outIs('NAME')
             ->atomIs('Arrayliteral', self::WITH_CONSTANTS)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('Staticclass', 'String'), self::WITH_CONSTANTS)
             ->inIs('ARGUMENT')
             ->inIs('DEFINITION')
             ->atomIs(array('Method', 'Magicmethod'))
             ->isNot('static', true)
             ->back('first');
        $this->prepareQuery();

        // 'a::m'() ; class a { function m() {}}
        $this->atomIs('Functioncall')
             ->outIs('NAME')
             ->atomIs('String', self::WITH_CONSTANTS)
             ->inIs('DEFINITION')
             ->atomIs(array('Method', 'Magicmethod'))
             ->isNot('static', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
