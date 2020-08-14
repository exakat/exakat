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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class WeirdIndex extends Analyzer {
    public function analyze(): void {

        // $a[' a']
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs('String', self::WITH_CONSTANTS)
             ->regexIs('noDelimiter', '^\\\s')
             ->back('first');
        $this->prepareQuery();

        // $a['a ']
        $this->atomIs('Array')
             ->analyzerIsNot('self')
             ->outIs('INDEX')
             ->atomIs('String', self::WITH_CONSTANTS)
             ->regexIs('noDelimiter', '\\\s\\$')
             ->back('first');
        $this->prepareQuery();

        // case ' a'
        $this->atomIs('Case')
             ->outIs('CASE')
             ->atomIs('String', self::WITH_CONSTANTS)
             ->regexIs('noDelimiter', '^\\\s')
             ->back('first');
        $this->prepareQuery();

        // case 'a '
        $this->atomIs('Case')
             ->analyzerIsNot('self')
             ->outIs('CASE')
             ->atomIs('String', self::WITH_CONSTANTS)
             ->regexIs('noDelimiter', '\\\s\\$')
             ->back('first');
        $this->prepareQuery();

        // array(' a' => 2)
        $this->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->as('results')
             ->outIs('INDEX')
             ->atomIs('String', self::WITH_CONSTANTS)
             ->regexIs('noDelimiter', '^\\\s')
             ->back('results');
        $this->prepareQuery();

        // array( 'a ' => 3)
        $this->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->as('results')
             ->analyzerIsNot('self')
             ->outIs('INDEX')
             ->atomIs('String', self::WITH_CONSTANTS)
             ->regexIs('noDelimiter', '\\\s\\$')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
