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

class CouldBeArray extends CouldBeType {
    // dependsOn is in CouldBeType class
    
    public function analyze() {
        // property with default
        $this->checkPropertyDefault(array('Arrayliteral'));

        // property relayed default
        $this->checkPropertyRelayedDefault(array('Arrayliteral'));

        // property relayed typehint
        $this->checkPropertyRelayedTypehint(array('Scalartypehint'), array('\\array'));

        // property relayed typehint
        $this->checkPropertyWithCalls(array('Scalartypehint'), array('\\array'));
        $this->checkPropertyWithPHPCalls('array');

        // return type
        $this->checkReturnedAtoms(array('Arrayliteral'));

        $this->checkReturnedCalls(array('Scalartypehint'), array('\\array'));

        $this->checkReturnedPHPTypes('array');

        $this->checkReturnedDefault(array('Arrayliteral'));

        $this->checkReturnedTypehint(array('Scalartypehint'), array('\\array'));

        // argument type
        // $arg[]
        $this->checkArgumentUsage(array('Variablearray'));

        // function ($a = array())
        $this->checkArgumentDefaultValues(array('Arrayliteral'));

        // function ($a) { bar($a);} function bar(array $b) {}
        $this->checkRelayedArgument(array('Scalartypehint'), array('\\array'));

        // function ($a) { array_diff($a);}
        $this->checkRelayedArgumentToPHP('array');

        // is_string
        $this->checkArgumentValidation(array('\\is_array'), array('Arrayliteral'));
    }
}

?>
