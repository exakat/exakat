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

class SubstrToTrim extends Analyzer {
    public function analyze() {
        //$b = substr($a, 1); //ltrim
        $this->atomFunctionIs('\substr')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('Integer', self::WITH_CONSTANTS)
             ->is('intval', 1)
             ->back('first')
             ->noChildWithRank('ARGUMENT', 2)
             ->back('first');
        $this->prepareQuery();

        //$b = substr($a, 0, -1); // rtrim
        $this->atomFunctionIs('\substr')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('Integer', self::WITH_CONSTANTS)
             ->is('intval', 0)
             ->back('first')
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('Integer', self::WITH_CONSTANTS)
             ->is('intval', -1)
             ->back('first');
        $this->prepareQuery();

        //$b = substr($a, 1, -1); // trim
        $this->atomFunctionIs('\substr')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('Integer', self::WITH_CONSTANTS)
             ->is('intval', 1)
             ->back('first')
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('Integer', self::WITH_CONSTANTS)
             ->is('intval', -1)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
