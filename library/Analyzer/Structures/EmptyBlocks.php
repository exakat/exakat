<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Analyzer\Structures;

use Analyzer;

class EmptyBlocks extends Analyzer\Analyzer {
    public function analyze() {
        // Empty block
        $this->atomIs(array('Switch', 'For', 'While', 'Foreach', 'Declare'))
             ->outIs(array('CASES', 'BLOCK'))
             ->hasNoOut('ELEMENT')
             ->back('first');
        $this->prepareQuery();

        // Empty block on ifthen
        $this->atomIs('Ifthen')
             ->outIs(array('THEN', 'ELSE'))
             ->hasNoOut(array('ELEMENT', 'CONDITION'))
             ->back('first')
             ->inIsIE('ELSE')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // Block with only one empty expression
        // Block with only empty expressions
        $this->atomIs(array('For', 'While', 'Foreach', 'Dowhile', 'Declare', 'Namespace', 'Declare'))
             ->analyzerIsNot('self')
             ->outIs('BLOCK')
             ->raw('where( __.out("ELEMENT").not( hasLabel("Void")).count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();

        // Empty block on ifthen
        $this->atomIs('Ifthen')
             ->outIs(array('THEN', 'ELSE'))
             ->raw('where( __.out("ELEMENT", "CONDITION").not( hasLabel("Void")).count().is(eq(0)) )')
             ->back('first')
             ->inIsIE('ELSE')
             ->analyzerIsNot('self');
        $this->prepareQuery();

    }
}

?>
