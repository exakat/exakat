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

class InvalidPackFormat extends Analyzer {
    public function dependsOn(): array {
        return array('Type/Pack',
                    );
    }


    public function analyze(): void {
        // pack('nvcT', $s)
        $this->atomFunctionIs('\\unpack')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             // Those steps weed out some non-formats
             ->analyzerIs('Type/Pack')
             ->raw('has("noDelimiter", neq(""))')
             // This regex include names in the format string, for unpacking
             ->regexIsNot('noDelimiter', '^([@0-9aAhHcCsSnviIlLNVqQJPfgGdeExXZ](\\\\*|\\\\d+)?(\\\\w+\\\\/?)?)+\$')
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs('\\pack')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             // Those steps weed out some non-formats
             ->analyzerIs('Type/Pack')
             ->raw('has("noDelimiter", neq(""))')
             // This regex include names in the format string, for packing
             ->regexIsNot('noDelimiter', '^([@0-9aAhHcCsSnviIlLNVqQJPfgGdeExXZ](\\\\*|\\\\d+)?)+\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
