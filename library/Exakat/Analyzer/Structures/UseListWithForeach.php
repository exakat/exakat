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

class UseListWithForeach extends Analyzer {
    public function analyze() {
        // foreach($a as $b) { list($d) = $b; }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('fullcode', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('fullcode', 'blind')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('List')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as list($b, $c)) { list($e, $f) = $b; }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('List')
             ->atomInsideNoDefinition('Variable')
             ->savePropertyAs('fullcode', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Variable')
             ->samePropertyAs('fullcode', 'blind')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('List')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $b) { $b[2]; }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('fullcode', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Array')
             ->is('isRead', true)
             ->outIs('VARIABLE')
             ->atomIs('Variablearray')
             ->samePropertyAs('fullcode', 'blind')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->atomIs(array('Integer', 'String'))
             ->hasNoOut('CONCAT') // Avoid built strings.
             ->back('first');
        $this->prepareQuery();
    }
}

?>
