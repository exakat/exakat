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

class CouldUseArrayFillKeys extends Analyzer {
    public function analyze() {
        // foreach($a as $b) { $c[$b] = 3; }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->savePropertyAs('fullcode', 'index')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Array')
             ->outIs('INDEX')
             ->samePropertyAs('fullcode', 'index')
             ->inIs('INDEX')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->noFullcodeInside('index')
             ->atomIsNot('Variable')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $b => $c) { $c[$b] = 3; }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIs(array('INDEX', 'VALUE'))
             ->savePropertyAs('fullcode', 'index')
             ->inIs(array('INDEX', 'VALUE'))
             ->outIs(array('INDEX', 'VALUE'))
             ->notSamePropertyAs('fullcode', 'index')
             ->savePropertyAs('fullcode', 'secondary')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Array')
             ->outIs('INDEX')
             ->samePropertyAs('fullcode', 'index')
             ->inIs('INDEX')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->noFullcodeInside('index')
             ->noFullcodeInside('secondary')
             ->atomIsNot('Variable')
             ->back('first');
        $this->prepareQuery();

        //foreach($a as &$v) { $v = constant}
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('VALUE')
             ->is('reference', true)
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->_as('block')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->samePropertyAs('code', 'blind')
             ->back('block')
             ->outIs('RIGHT')
             ->is('constant', true)
             ->back('first');
        $this->prepareQuery();

        //array_map(function() { return 1}, $a)
        $this->atomFunctionIs('\\array_map')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Closure')
             ->outIs('BLOCK')
             ->atomInside('Return')
             ->is('constant', true)
             ->back('first');
        $this->prepareQuery();

        //array_map(function() {}, $a)
        $this->atomFunctionIs('\\array_map')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Closure')
             ->outIs('BLOCK')
             ->noAtomInside('Return')
             ->back('first');
        $this->prepareQuery();

        //array_map('foo', $a)
        $this->atomFunctionIs('\\array_map')
             ->outWithRank('ARGUMENT', 0)
             // array, string, identifier...
             ->inIs('DEFINITION')
             // method, function...
             ->outIs('BLOCK')
             ->atomInside('Return')
             ->is('constant', true)
             ->back('first');
        $this->prepareQuery();

    }
}

?>
