<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class UselessCheck extends Analyzer {
    public function analyze() {
        //    if (count($anArray) > 0){    foreach ($anArray as $el){
        $this->atomIs('Ifthen')
             ->hasNoOut('ELSE')
             ->outIs('CONDITION')
             ->atomInsideNoDefinition('Functioncall')
             // count($a) > 0, sizeof($a) != 0, !empty($a)
             ->functioncallIs(array('\\count', '\\sizeof'))
             ->outWithRank('ARGUMENT', 0)
             ->savePropertyAs('fullcode', 'var')
             ->back('first')
             ->outIs('THEN')
             ->is('count', 1)
             ->outWithRank('EXPRESSION', 0)
             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->samePropertyAs('fullcode', 'var')
             ->back('first');
        $this->prepareQuery();

        //    if (count($anArray) > 0){    foreach ($anArray as $el){
        $this->atomIs('Ifthen')
             ->analyzerIsNot('self')
             ->hasNoOut('ELSE')
             ->outIs('CONDITION')
             ->atomInsideNoDefinition('Empty')
             // count($a) > 0, sizeof($a) != 0, !empty($a)
             ->outWithRank('ARGUMENT', 0)
             ->savePropertyAs('fullcode', 'var')
             ->back('first')
             ->outIs('THEN')
             ->is('count', 1)
             ->outWithRank('EXPRESSION', 0)
             ->atomIs('Foreach')
             ->outIs('SOURCE')
             ->samePropertyAs('fullcode', 'var')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
