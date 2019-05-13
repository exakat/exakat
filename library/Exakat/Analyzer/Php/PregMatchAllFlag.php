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

class PregMatchAllFlag extends Analyzer {
    public function analyze() {
        // Using default configuration
        $this->atomFunctionIs('\preg_match_all')
             ->noChildWithRank('ARGUMENT', 3)
             ->outWithRank('ARGUMENT', 2)
             ->savePropertyAs('code', 'r')
             ->inIs('ARGUMENT')
             ->nextSiblings() // Do we really need all of them? May be limit to 3/5
             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('SOURCE')
             ->outIs('INDEX')
             ->savePropertyAs('code', 'k')
             ->inIs('INDEX')

             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Array')
             ->outIs('VARIABLE')// $r[1][$id]
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->samePropertyAs('code', 'k')
             ->back('first');
        $this->prepareQuery();

        // Using explicit configuration
        $this->atomFunctionIs('\preg_match_all')
             ->outWithRank('ARGUMENT', 3)
             ->atomIs(array('Identifier', 'Nsname'))
             ->fullnspathIsNot('\PREG_SET_ORDER')
             ->inIs('ARGUMENT')
             ->outWithRank('ARGUMENT', 2)
             ->savePropertyAs('code', 'r')
             ->inIs('ARGUMENT')
             ->nextSiblings() // Do we really need all of them? May be limit to 3/5

             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('SOURCE')
             ->outIs('INDEX')
             ->savePropertyAs('code', 'k')
             ->inIs('INDEX')

             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Array')
             ->outIs('VARIABLE')// $r[1][$id]
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'r')
             ->inIs('VARIABLE')
             ->inIs('VARIABLE')
             ->outIs('INDEX')
             ->samePropertyAs('code', 'k')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
