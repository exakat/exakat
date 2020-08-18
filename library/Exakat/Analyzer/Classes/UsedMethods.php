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

class UsedMethods extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenMethods',
                     'Complete/SetClassMethodRemoteDefinition',
                     'Complete/SetClassRemoteDefinitionWithLocalNew',
                     'Complete/SetClassRemoteDefinitionWithReturnTypehint',
                     'Complete/SetStringMethodDefinition',
                     'Complete/SetArrayClassDefinition',
                    );
    }

    public function analyze(): void {
        $this->atomIs(array('Method', 'Magicmethod'))
             ->outIs('DEFINITION')
             ->atomIs(array('Methodcall', 'Staticmethodcall', 'String', 'Arrayliteral'))
             ->back('first');
        $this->prepareQuery();

        // Private constructors
        $this->atomIs(self::CLASSES_ALL)
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->analyzerIsNot('self')
             ->is('visibility', 'private')
             ->as('used')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->back('first')
             ->outIs('METHOD')
             ->atomInsideNoDefinition('New')
             ->outIs('NEW')
             ->tokenIs(self::STATICCALL_TOKEN)
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('used');
        $this->prepareQuery();

        // Normal Constructors
        $this->atomIs(self::CLASSES_ALL)
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->analyzerIsNot('self')
             ->isNot('visibility', 'private')
             ->as('used')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->back('first')
             ->outIs('DEFINITION')
             ->hasIn('NEW')
             ->back('used');
        $this->prepareQuery();
    }
}

?>
