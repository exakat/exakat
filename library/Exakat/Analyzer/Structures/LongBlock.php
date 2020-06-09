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

class LongBlock extends Analyzer {
    protected $longBlock = 200;

    public function analyze() {
        // do { <may lines> } while();
        $this->atomIs(array('While', 'Dowhile', 'For', 'Foreach', 'Ifthen'))
             ->outIs(array('BLOCK', 'THEN', 'ELSE'))
             ->outWithRank('EXPRESSION', 'first')
             ->savePropertyAs('line', 'begin')
             ->inIs('EXPRESSION')
             
             ->outWithRank('EXPRESSION', 'last')
             ->savePropertyAs('line', 'finish')
             
             ->raw('filter{ finish - begin > '.$this->longBlock.' }')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
