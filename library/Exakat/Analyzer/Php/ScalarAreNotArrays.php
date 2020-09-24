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

class ScalarAreNotArrays extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/MakeClassMethodDefinition',
                     'Complete/CreateDefaultValues',
                     'Complete/SetClassRemoteDefinitionWithTypehint',
                    );
    }

    public function analyze(): void {
        // TODO : support for null ?

        // WIth typehint
        // function foo(in $x) { echo $x[2]; }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIs(array('\\int', '\\bool', '\\float'))
             ->inIs('TYPEHINT')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->atomIs('Variablearray')
             ->inIs('VARIABLE');
        $this->prepareQuery();

        // WIth typehint
        // function foo($x = 2) { echo $x[2]; }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->analyzerIsNot('self')
             ->outIs('DEFAULT')
             ->atomIs(array('Boolean', 'Integer', 'Float', 'Null'))
             ->inIs('DEFAULT')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->atomIs('Variablearray')
             ->inIs('VARIABLE')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // WIth typehint (here, null is the most important)
        // function foo(?A $x) { echo $x[2]; }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->analyzerIsNot('self')
             ->hasOut('TYPEHINT')
             ->isNullable()
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->atomIs('Variablearray')
             ->inIs('VARIABLE')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // WIth return typehint
        // echo $a[2]; $a = foo(); function foo() : int {}
        $this->atomIs('Variablearray')
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->atomIs(self::FUNCTIONS_CALLS)
             ->inIs('DEFINITION')
             ->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->fullnspathIs(array('\\int', '\\bool', '\\float', '\\void', '\\callable'))
             ->back('first')
             ->inIs('VARIABLE')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // echo $a[2]; $a = foo(); function foo() : ?int {}
        $this->atomIs('Variablearray')
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->atomIs(self::FUNCTIONS_CALLS)
             ->inIs('DEFINITION')
             ->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->isNullable()
             ->back('first')
             ->inIs('VARIABLE');
        $this->prepareQuery();

        // With argument's default value
        // function foo(int $x) { echo $x[2]; }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('DEFAULT')
             ->fullnspathIs(array('\\int', '\\bool', '\\float', '\\null'))
             ->inIs('DEFAULT')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->atomIs('Variablearray')
             ->inIs('VARIABLE')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // foo(1.2); function foo($a) { $a[3]; }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('DEFINITION')
             ->outIs('ARGUMENT')
             ->atomIs(array('Boolean', 'Integer', 'Float', 'Null'))
             ->goToParameterDefinition()

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->atomIs('Variablearray')
             ->inIs('VARIABLE')
             ->analyzerIsNot('self');
        $this->prepareQuery();
    }
}

?>
