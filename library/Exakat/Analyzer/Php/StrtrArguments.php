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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class StrtrArguments extends Analyzer {
    public function analyze(): void {
        // strtr($a, $b, '') is always useless
        $this->atomFunctionIs('\\strtr')
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->noDelimiterIs('')
             ->back('first');
        $this->prepareQuery();

        // strtr($a, 'ab', 'cde') has different size in arguments
        // strtr($a, 'abc', 'cd') has different size in arguments
        $this->atomFunctionIs('\\strtr')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->noDelimiterIsNot('')
             ->getStringLength('noDelimiter', 's1')
             ->inIs('ARGUMENT')
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->noDelimiterIsNot('')
             ->getStringLength('noDelimiter', 's2')
             ->raw('filter{s1 != s2}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
