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

class DefinedParentMP extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/SetParentDefinition',
                     'Complete/MakeClassConstantDefinition',
                     'Complete/MakeClassMethodDefinition',
                     'Complete/OverwrittenProperties',
                     'Complete/OverwrittenConstants',
                     'Complete/OverwrittenMethods',
                     'Classes/IsExtClass',
                    );
    }

    public function analyze(): void {
        // parent::methodcall()
        $this->atomIs('Parent')
             ->inIs('CLASS')
             ->atomIs('Staticmethodcall')
             ->as('results')
             ->inIs('DEFINITION')
             ->isNot('visibility', 'private')
             ->back('results');
        $this->prepareQuery();

        // parent::constant
        $this->atomIs('Parent')
             ->inIs('CLASS')
             ->atomIs('Staticconstant')
             ->as('results')
             ->inIs('DEFINITION')
             ->inIs('CONST') // just for constants
             ->isNot('visibility', 'private')
             ->back('results');
        $this->prepareQuery();

        // parent::$property
        $this->atomIs('Parent')
             ->inIs('CLASS')
             ->atomIs('Staticproperty')
             ->as('results')
             ->inIs('DEFINITION')
             ->inIs('PPP')
             ->isNot('visibility', 'private')
             ->back('results');
        $this->prepareQuery();

        $this->atomIs('Parent')
             ->inIs('CLASS')
             ->atomIs('Staticproperty')
             ->as('results')
             ->inIs('DEFINITION')
             ->inIs('PPP')
             ->atomIs('Virtualproperty')
             ->outIs('OVERWRITE')
             ->atomIs('Propertydefinition')
             ->inIs('PPP')
             ->isNot('visibility', 'private')
             ->back('results');
        $this->prepareQuery();

        // handle PHP/extensions case
        $this->atomIs('Parent')
             ->inIs('CLASS') // Check it has ::
             ->as('results')
             ->analyzerIsNot('self')
             ->atomIsNot('Staticclass')
             ->goToClass()
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('EXTENDS')
             ->raw('or( __.has("isPhp", true), __.has("isStub", true), __.has("isExt", true))')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
