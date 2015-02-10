<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Constants;

use Analyzer;

class MultipleConstantDefinition extends Analyzer\Analyzer {
    public function analyze() {
        // case-sensitive constants
        $this->atomIs('Functioncall')
             ->code('define')
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 'first')
             ->atomIs('String')
             ->raw('groupCount(m){it.noDelimiter}.aggregate().filter{m[it.noDelimiter] > 1}');
        $this->prepareQuery();

        // case-insensitive constants
        $this->atomIs('Functioncall')
             ->code('define')
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 2)
             ->atomIs('Boolean')
             ->code('true', true)
             ->inIs('ARGUMENT')
             ->rankIs('ARGUMENT', 'first')
             ->atomIs('String')
             ->raw('groupCount(m){it.noDelimiter.toLowerCase()}.aggregate().filter{m[it.noDelimiter.toLowerCase()] > 1}');
        $this->prepareQuery();
    }
}

?>
