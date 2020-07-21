<?php
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

class CouldBeCallable extends CouldBeType {
    public function analyze() {
        $callableAtoms = array('Closure', 'Arrowfunction');

        // property, as assigned with closure or arrowfunction
        $this->checkPropertyDefault($callableAtoms);
        // closure and arrowFunction can't be a real default

        // property : based on default value of feeding argument
        $this->checkPropertyRelayedTypehint(array('Scalartypehint'), array('\\callable'));

        // property relayed typehint
        $this->checkPropertyWithCalls(array('Scalartypehint'), array('\\callable'));
        $this->checkPropertyWithPHPCalls('callable');

        // property called as fucntion ($this->a)()
        // ($this->property)($args) parenthesis are compulsory
        $this->atomIs('Ppp')
             ->filter(
                $this->side()
                     ->outIs('TYPEHINT')
                     ->atomIs('Void')
             )
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->outIs('DEFINITION')
             ->inIs('CODE')
             ->atomIs('Functioncall')
             ->back('first');
        $this->prepareQuery();

        // return type
        $this->checkReturnedAtoms($callableAtoms);

        $this->checkReturnedCalls(array('Scalartypehint'), array('\\callable'));

        $this->checkReturnedPHPTypes('callable');

        $this->checkReturnedDefault($callableAtoms);

        $this->checkReturnedTypehint(array('Scalartypehint'), array('\\callable'));

        // (foo())($arg2)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->filter(
                $this->side()
                     ->outIs('TYPEHINT')
                     ->atomIs('Void')
             )
             ->inIs('NAME')
             ->inIsIE('CODE')
             ->atomIs('Functioncall')
             ->back('first');
        $this->prepareQuery();

        // function ($a = array())
        $this->checkArgumentDefaultValues($callableAtoms);

        // function ($a) { bar($a);} function bar(array $b) {}
        $this->checkRelayedArgument(array('Scalartypehint'), array('\\callable'));

        // function ($a) { array_diff($a);}
        $this->checkRelayedArgumentToPHP('callable');
        
        // argument validation
        $this->checkArgumentValidation(array('\\is_callable'), $callableAtoms);

        // $arg(...) with parameter
        $this->atomIs('Parameter')
             ->filter(
                $this->side()
                     ->outIs('TYPEHINT')
                     ->atomIs('Void')
             )
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('NAME')
             ->inIsIE('CODE')
             ->atomIs('Functioncall')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
