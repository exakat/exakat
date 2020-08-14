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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class ArrayKeyExistsWithObjects extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/CreateDefaultValues',
                     'Php/ScalarTypehintUsage',
                    );
    }

    public function analyze(): void {
        // WIth typehint
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('self')
             ->outIs('ARGUMENT')
             ->analyzerIsNot('Php/ScalarTypehintUsage')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('ARGUMENT')
             ->functioncallIs('\\array_key_exists');
        $this->prepareQuery();

        // WIth return typehint
        // array_key_exists('', $a); $a = foo(); function foo() : TTT {}
        $this->atomFunctionIs('\\array_key_exists')
             ->analyzerIsNot('self')
             ->outWIthRank('ARGUMENT', 1)
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->atomIs(self::FUNCTIONS_CALLS)
             ->inIs('DEFINITION')
             ->atomIs(self::FUNCTIONS_ALL)
             ->hasOut('RETURNTYPE')
             ->analyzerIsNot('Php/ScalarTypehintUsage')
             ->back('first');
        $this->prepareQuery();

        // WIth object operator
        // array_key_exists('', $a); $a->p  = 2;
        $this->atomFunctionIs('\\array_key_exists')
             ->analyzerIsNot('self')
             ->outWIthRank('ARGUMENT', 1)
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->outIs('DEFINITION')
             ->atomIs('Variableobject')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
