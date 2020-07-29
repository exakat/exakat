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

class CouldBeInt extends CouldBeType {
    public function analyze() : void {
        $intAtoms = array('Integer', 'Addition', 'Multiplication', 'Not', 'Power');
        
        // property : based on default value (created or not)
        $this->checkPropertyDefault($intAtoms);

        $this->checkPropertyRelayedDefault($intAtoms);

        // property relayed typehint
        $this->checkPropertyRelayedTypehint(array('Scalartypehint'), array('\\int'));

        // property relayed typehint
        $this->checkPropertyWithCalls(array('Scalartypehint'), array('\\int'));
        $this->checkPropertyWithPHPCalls('int');

        // argument type : $x[$arg]
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFINITION')
             ->inIs('INDEX')
             ->atomIs(array('Array'))
             ->back('first');
        $this->prepareQuery();

        // argument type : $x[$arg]
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Assignation'))
             ->codeIs(array('+=', '-=', '*=', '%=', '/=', '**='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // return type
        $this->checkReturnedAtoms($intAtoms);

        $this->checkReturnedCalls(array('Scalartypehint'), array('\\int'));

        $this->checkReturnedPHPTypes('int');

        $this->checkReturnedDefault($intAtoms);

        $this->checkReturnedTypehint(array('Scalartypehint'), array('\\int'));

        // argument type : $x[$arg]
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('INDEX')
             ->atomIs(array('Array'))
             ->back('result');
        $this->prepareQuery();

        // argument type : $x[$arg]
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Assignation'))
             ->codeIs(array('+=', '-=', '*=', '%=', '/=', '**='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->back('result');
        $this->prepareQuery();

        // function ($a = int)
        $this->checkArgumentDefaultValues($intAtoms);

        // function ($a) { bar($a);} function bar(array $b) {}
        $this->checkRelayedArgument(array('Scalartypehint'), array('\\int'));

        // function ($a) { pow($a, 2);}
        $this->checkRelayedArgumentToPHP('int');
        
        // argument validation
        $this->checkArgumentValidation(array('\\is_int'), $intAtoms);

        // (int) or intval
        $this->checkCastArgument('T_INT_CAST', array('\\intval'));

        // argument because used in a specific operation
        // $arg && ''
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Addition', 'Power', 'Multiplication'))
             ->back('result');
        $this->prepareQuery();
        
        // May also cover if( $arg).,
        // May also cover coalesce, ternary.
        // short assignations
    }
}

?>
