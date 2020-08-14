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

class AvoidOptionalProperties extends Analyzer {
    public function dependsOn(): array {
        return array('Classes/Constructor',
                     'Complete/CreateDefaultValues',
                     'Complete/SetClassRemoteDefinitionWithGlobal',
                     'Complete/SetClassRemoteDefinitionWithInjection',
                     'Complete/SetClassRemoteDefinitionWithLocalNew',
                     'Complete/SetClassRemoteDefinitionWithParenthesis',
                     'Complete/SetClassRemoteDefinitionWithReturnTypehint',
                     'Complete/SetClassRemoteDefinitionWithTypehint',
                     'Complete/SetClassMethodRemoteDefinition',
                    );
    }

    public function analyze(): void {
        // if ($this->p)  {}
        $this->atomIs('Member')
             ->hasIn('CONDITION')
             ->inIs('DEFINITION')
             ->isMissingOrNull()
             ->back('first');
        $this->prepareQuery();

        // if (empty($this->a))
        $this->atomIs('Member')
             ->inIs('ARGUMENT')
             ->atomIs(array('Empty', 'Isset'))
             ->as('results')
             ->back('first')
             ->inIs('DEFINITION')
             ->isMissingOrNull()
             ->back('results');
        $this->prepareQuery();

        // if (is_null($this->a))
        $this->atomIs('Member')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->back('first')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->inIs('ARGUMENT')
             ->functioncallIs('\\is_null')
             ->as('results')
             ->goToClass()
             ->outIs('PPP')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->samePropertyAs('propertyname', 'name', self::CASE_INSENSITIVE)
             ->isMissingOrNull()
             ->back('results');
        $this->prepareQuery();

        // $this->a == null
        $this->atomIs('Member')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->back('first')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Null')
             ->back('results')
             ->goToClass()
             ->outIs('PPP')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->samePropertyAs('propertyname', 'name', self::CASE_INSENSITIVE)
             ->isMissingOrNull()
             ->back('results');
        $this->prepareQuery();

        // class x { function x($a = null) {} ...}
        $this->atomIs('Method')
             ->analyzerIs('Classes/Constructor')
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->inIs('TYPEHINT')
             ->outIs('DEFAULT')
             ->atomIs('Null', self::WITH_CONSTANTS)
             ->hasNoIn('RIGHT')
             ->back('first');
        $this->prepareQuery();
    }
}

?>