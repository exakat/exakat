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

namespace Exakat\Analyzer\Patterns;

use Exakat\Analyzer\Analyzer;

class DependencyInjection extends Analyzer {
    public function dependsOn(): array {
        return array('Classes/Constructor',
                    );
    }

    public function analyze(): void {
        $scalars = $this->loadIni('php_scalar_types.ini', 'types');

        // Assigned to a property at constructor
        $this->atomIs('Magicmethod')
             ->analyzerIs('Classes/Constructor')
             ->outIs('ARGUMENT')
             ->as('result')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->fullnspathIsNot($scalars)
             ->inIs('TYPEHINT')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'arg')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'arg')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Member')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->back('result');
        $this->prepareQuery();

        // Assigned to a static property at constructor
        $this->atomIs('Magicmethod')
             ->analyzerIs('Classes/Constructor')
             ->outIs('ARGUMENT')
             ->as('result')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->fullnspathIsNot($scalars)
             ->inIs('TYPEHINT')
             ->outIsIE('LEFT')
             ->savePropertyAs('code', 'arg')
             ->back('first')
             ->inIs('MAGICMETHOD')
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'arg')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('result');
        $this->prepareQuery();

    }
}

?>
