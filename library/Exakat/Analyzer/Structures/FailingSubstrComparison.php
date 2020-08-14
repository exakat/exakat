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

class FailingSubstrComparison extends Analyzer {

    public function analyze(): void {
        // substr($a, 0, 3) === 'abcdef';
        $this->atomIs('Comparison')
             ->codeIs(array('==', '==='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->outIs(array('LEFT', 'RIGHT'))
             ->functioncallIs('\substr')
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('Integer', self::WITH_CONSTANTS)
             ->isMore('intval', 0)
             ->savePropertyAs('intval', 'length')
             ->back('first')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->has('noDelimiter')
             ->getStringLength('noDelimiter', 's')
             // Substring is actually as long as length
             ->raw('filter{ s != length.toInteger().abs(); }')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
