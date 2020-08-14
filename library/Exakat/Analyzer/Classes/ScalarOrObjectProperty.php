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

class ScalarOrObjectProperty extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/CreateDefaultValues',
                     'Complete/SetClassRemoteDefinitionWithReturnTypehint',
                    );
    }

    public function analyze(): void {
        // todo : extend to array  : warning : string-array syntax
        // Property defined as literal, used as object
        $this->atomIs(self::CLASSES_ALL)
             ->outIs('PPP')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->as('results')
             ->analyzerIsNot('self')
             ->outIs('DEFAULT')
             ->atomIsNot(array('Void', 'Null'))
             ->isLiteral()
             ->inIs('DEFAULT')
             ->outIs('DEFINITION')
             ->inIs(array('OBJECT', 'CLASS')) // Good for methodcall and properties
             ->back('results');
        $this->prepareQuery();

        // Property defined as object, assigned as literal
        $this->atomIs(self::CLASSES_ALL)
             ->outIs('PPP')
             ->outIs('PPP')
             ->analyzerIsNot('self')
             ->as('results')

             ->outIs('DEFAULT')
             ->atomIs('New') // at least ONE default is a NEW
             ->inIs('DEFAULT')

             ->outIs('DEFAULT')
             ->hasIn('RIGHT')
             ->atomIs(self::LITERALS) // Another definition is a literal
             ->atomIsNot('Null')

             ->back('results');
        $this->prepareQuery();

        // Property with typehint, assigned as literal
        $this->atomIs(self::CLASSES_ALL)
             ->outIs('PPP')
             ->outIs('TYPEHINT')
             ->atomisNot('Void')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
             ->inIs('TYPEHINT')
             ->outIs('PPP')
             ->analyzerIsNot('self')
             ->as('results')

             ->outIs('DEFAULT')
             ->hasIn('RIGHT')
             ->atomIs(self::LITERALS) // Another definition is a literal
             ->atomIsNot('Null')

             ->back('results');
        $this->prepareQuery();

        // Property defined as object, assigned as literal (methodcall version)
        $this->atomIs(self::CLASSES_ALL)
             ->outIs('PPP')
             ->outIs('PPP')
             ->analyzerIsNot('self')
             ->as('results')

             ->outIs('DEFAULT')
             ->atomIs(array('Methodcall', 'Functioncall', 'Staticmethodcall'))
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')
             ->atomIsNot('Void')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable', '\\void'))
             ->back('results')

             ->outIs('DEFAULT')
             ->atomIs(self::LITERALS) // Another definition is a literal
             ->atomIsNot('Null')

             ->back('results');
        $this->prepareQuery();
    }
}

?>
