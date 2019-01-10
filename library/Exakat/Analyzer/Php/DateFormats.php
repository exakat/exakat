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

class DateFormats extends Analyzer {
    public function analyze() {
        // date('r');
        $this->atomFunctionIs(array('\date', '\strftime', '\gmstrftime'))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String');
        $this->prepareQuery();

        // DateTime->format('r');
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs('format')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String');
        $this->prepareQuery();

        // date_format('r');
        $this->atomFunctionIs('\date_format')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String');
        $this->prepareQuery();
    }
}

?>
