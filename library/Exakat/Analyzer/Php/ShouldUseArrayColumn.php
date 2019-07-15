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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class ShouldUseArrayColumn extends Analyzer {
    public function analyze() {
        // foreach($a as $b) { $c[] = $b->e; }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'name')
             ->back('first')

             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomInsideNoDefinition('Assignation')
             ->hasNoInstruction(array('Ifthen', 'Switch')) // Make this a filter
             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             // The left part is not reusing the blin variable : this would be too complex for array_column
             ->not(
                $this->side()
                     ->atomInsideNoDefinition(array('Variable', 'Variablearray'))
                     ->samePropertyAs('code', 'name')
             )
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs(array('Array', 'Member'))
             ->outIs(array('VARIABLE', 'OBJECT'))
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // for($i = 0; $i < count($n); ++$i) { $c[] = $n[$i]['c']; }
        $this->atomIs('For')
             ->outIs('INCREMENT')
             ->outIs('EXPRESSION')
             ->atomIs(array('Preplusplus', 'Postplusplus'))
             ->outIs(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->atomIs('Variable')
             ->savePropertyAs('code', 'name')
             ->back('first')

             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')

             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             ->inIs('LEFT')

             ->outIs('RIGHT')
             ->atomIs('Array')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'name')
             ->inIs('INDEX')
             ->back('first');
        $this->prepareQuery();

        // for($i = 0; $i < count($n); ++$i) { $c[$i] = $n[$i]['c']; }
        $this->atomIs('For')
             ->outIs('INCREMENT')
             ->outIs('EXPRESSION')
             ->atomIs(array('Preplusplus', 'Postplusplus'))
             ->outIs(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->atomIs('Variable')
             ->savePropertyAs('code', 'name')
             ->back('first')

             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')
             ->_as('exp')

             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'name')
             ->back('exp')

             ->outIs('RIGHT')
             ->atomIs('Array')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('code', 'name')
             ->inIs('INDEX')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
