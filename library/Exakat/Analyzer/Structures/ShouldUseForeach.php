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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ShouldUseForeach extends Analyzer {
    public function analyze() {
        // for($i = 0; $i < $n; ++$i) {}
        $this->atomIs('For')

             ->outIs('INIT')
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')
             ->_as('init')
             ->outIs('RIGHT')
             ->codeIs('0')
             ->back('init')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')

             ->outIs('INCREMENT')
             ->outIs('EXPRESSION')
             ->atomIs(array('Postplusplus', 'Preplusplus'))
             ->outIs(array('POSTPLUSPLUS', 'PREPLUSPLUS'))
             ->samePropertyAs('code', 'blind')
             ->back('first')
             
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
