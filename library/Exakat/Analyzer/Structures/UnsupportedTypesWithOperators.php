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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UnsupportedTypesWithOperators extends Analyzer {
    /* PHP version restrictions
    protected $phpVersion = '7.4-';
    */

    public function dependsOn(): array {
        return array('Complete/CreateDefaultValues',
                    );
    }

    public function analyze(): void {
//+, -, *, /, **, %, <<, >>, &, |, ^, ~, ++, --

        $atoms = array('Addition',
                       'Multiplication',
                       'Bitoperation',
                       'Bitshift',
                       'Not',
                       'Power',
                       'Postplusplus',
                       'Preplusplus',
                       'Keyvalue',
                       'Array',
                       );

        $links = array('LEFT', 'RIGHT', 'NOT', 'PREPLUSPLUS', 'POSTPLUSPLUS', 'INDEX');

        // array() * 3
        $this->atomIs($atoms)
             ->outIs($links)
             ->atomIs(array('Arrayliteral', 'New', 'Clone'), self::WITH_CONSTANTS)
             ->back('first')
             // array() + array()
             ->not(
                $this->side()
                     ->atomIs('Addition')
                     ->codeIs('+')
                     ->outIs('RIGHT')
                     ->atomIs(array('Arrayliteral'), self::WITH_CONSTANTS)
                     ->inIs('RIGHT')
                     ->outIs('LEFT')
                     ->atomIs(array('Arrayliteral'), self::WITH_CONSTANTS)
             );
        $this->prepareQuery();

        // foo() : array  * 3
        $this->atomIs($atoms)
             ->outIs($links)
             ->atomIs('Functioncall', self::WITH_CONSTANTS)
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')
             ->atomIs('Scalartypehint')
             ->fullnspathIs(array('\\array', '\\resource'))
             ->back('first');
        $this->prepareQuery();

        // foo() : A\B * 3
        $this->atomIs($atoms)
             ->outIs($links)
             ->atomIs('Functioncall', self::WITH_CONSTANTS)
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')
             ->atomIs(self::STATIC_NAMES)
             ->back('first');
        $this->prepareQuery();

        // PHP native functions
        // Typed arguments and properties
        // typed constants (via its value)
    //    $c = array_filter($a);

    }
}

?>
