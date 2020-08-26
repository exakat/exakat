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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;
use Exakat\Query\DSL\FollowParAs;

class UselessCheck extends Analyzer {
    public function analyze(): void {
        // No check on empty() and isset(), as they also check the variable existence
        //    if (count($anArray) > 0){    foreach ($anArray as $el){
        $this->atomIs('Ifthen')
             ->hasNoOut('ELSE')
             ->outIs('CONDITION')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->outIsIE(array('LEFT', 'RIGHT'))
             // count($a) > 0, sizeof($a) != 0
             ->functioncallIs(array('\\count', '\\sizeof'))
             ->outWithRank('ARGUMENT', 0)
             ->savePropertyAs('fullcode', 'var')
             ->not(
                $this->side()
                     ->inIs('ARGUMENT')
                     ->inIs(array('LEFT', 'RIGHT'))
                     ->atomIs('Comparison')
                     ->outIs(array('LEFT', 'RIGHT'))
                     ->atomIs('Integer')
                     ->is('fullcode', 0)
             )
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
