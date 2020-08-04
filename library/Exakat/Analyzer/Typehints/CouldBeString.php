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
use Exakat\Query\DSL\FollowParAs;

class CouldBeString extends CouldBeType {

    public function analyze() : void {
        $stringAtoms = self::STRINGS_LITERALS;

        $this->checkPropertyDefault($stringAtoms);

        // property relayed default
        $this->checkPropertyRelayedDefault($stringAtoms);

        // property relayed typehint
        $this->checkPropertyRelayedTypehint(array('Scalartypehint'), array('\\string'));

        // property relayed typehint
        $this->checkPropertyWithCalls(array('Scalartypehint'), array('\\string'));
        $this->checkPropertyWithPHPCalls('string');

        // $a[$b->property] : could be a string or an integer
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFAULT')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->outIs('DEFINITION')
             ->inIs('INDEX')
             ->atomIs('Array')
             ->back('first');
        $this->prepareQuery();

        // return type
        $this->checkReturnedAtoms($stringAtoms);

        $this->checkReturnedCalls(array('Scalartypehint'), array('\\string'));

        $this->checkReturnedPHPTypes('string');

        $this->checkReturnedDefault($stringAtoms);

        $this->checkReturnedTypehint(array('Scalartypehint'), array('\\string'));

        // return type : return $a->b .= "s";
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('self')
             ->outIs('RETURNED')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->analyzerIsNot('self')
             ->atomIs('Assignation')
             ->codeIs(array('.='))
             ->back('first');
        $this->prepareQuery();

        // argument type
        // function ($a = array())
        $this->checkArgumentDefaultValues($stringAtoms);

        // function ($a) { bar($a);} function bar(array $b) {}
        $this->checkRelayedArgument(array('Scalartypehint'), array('\\string'));

        // function ($a) { array_diff($a);}
        $this->checkRelayedArgumentToPHP('string');

        // is_string
        $this->checkArgumentValidation(array('\\is_string'), $stringAtoms);
        
        // (string) or strval
        $this->checkCastArgument('T_STRING_CAST', array('\\strval'));

        // argument because used in a specific operation
        // $arg . ''
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->hasIn('CONCAT')
             ->back('result');
        $this->prepareQuery();

        // $a[$arg] : could be a string or an integer
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('INDEX')
             ->atomIs('Array')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
