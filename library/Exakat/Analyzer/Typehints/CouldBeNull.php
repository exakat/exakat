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

namespace Exakat\Analyzer\Typehints;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

class CouldBeNull extends CouldBeType {
    public function analyze() {
        $nullAtoms = array('Null');
        
        // property : based on default value (created or not)
        $this->checkPropertyDefault($nullAtoms);

        $this->checkPropertyRelayedDefault($nullAtoms);

        // property relayed typehint
        $this->checkPropertyRelayedTypehint(array('Null'), array());

        // property relayed typehint
        $this->checkPropertyWithCalls(array('Null'), array());
        $this->checkPropertyWithPHPCalls('null');

        // argument type : $x = $arg ?? 2;
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs(array('Coalesce'))
             ->back('first');
        $this->prepareQuery();

        // argument type : $this->x ??= 2;
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Assignation'))
             ->codeIs(array('??='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // return type
        $this->checkReturnedAtoms($nullAtoms);

        $this->checkReturnedCalls(array('Null'), array());

        $this->checkReturnedPHPTypes('null');

        $this->checkReturnedDefault($nullAtoms);

        $this->checkReturnedTypehint(array('Null'), array());

        // arguments
        // function ($a = int)
        $this->checkArgumentDefaultValues($nullAtoms);

        // function ($a) { bar($a);} function bar(array $b) {}
        $this->checkRelayedArgument(array('Null'), array());

        // function ($a) { pow($a, 2);}
        $this->checkRelayedArgumentToPHP('null');
        
        // argument validation
        $this->checkArgumentValidation(array('\\is_null'), $nullAtoms);

        // argument because used in a specific operation
        // $arg && ''
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Coalesce'))
             ->back('result');
        $this->prepareQuery();

        // $arg === null
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Comparison'))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Null')
             ->back('result');
        $this->prepareQuery();
        
        // May also cover if( $arg).,
        // May also cover coalesce, ternary.
        // short assignations
    }
}

?>
