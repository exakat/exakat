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

class NoSubstrOne extends Analyzer {
    public function analyze(): void {
        // Don't use substr($x, $y, 1) but $x[$y];
        $this->atomFunctionIs('\\substr')
             ->outWithRank('ARGUMENT', 2)
             ->is('intval', 1)
             ->back('first');
        $this->prepareQuery();

        // Don't use substr($x, -1) but $x[-1];
        $this->atomFunctionIs('\\substr')
             ->noChildWithRank('ARGUMENT', 2)
             ->outWithRank('ARGUMENT', 1)
             ->is('intval', -1)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
