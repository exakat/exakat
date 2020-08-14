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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class TooManyFinds extends Analyzer {
    protected $minimumFinds = 5;
    protected $findSuffix   = '';
    protected $findPrefix   = 'find';

    public function analyze(): void {
        // class x { function findY() {}}
        // suffix version
        if (!empty($this->findSuffix)) {
            $suffixes = str2array($this->findSuffix);
            $this->atomIs(self::CIT)
                 ->analyzerIsNot('self')
                 ->filter(
                    $this->side()
                         ->outIs('METHOD')
                         ->atomIs('Method')
                         ->outIs('NAME')
                         ->regexIs('fullcode', '(?i)(' . implode('|', $suffixes) . ')\\$')
                         ->count()
                         ->raw('is(gte(' . $this->minimumFinds . '))')
                 );
            $this->prepareQuery();
        }

        // class x { function findY() {}}
        // prefix version
        if (!empty($this->findPrefix)) {
            $prefixes = str2array($this->findPrefix);
            $this->atomIs(self::CIT)
                 ->analyzerIsNot('self')
                 ->filter(
                    $this->side()
                         ->outIs('METHOD')
                         ->atomIs('Method')
                         ->outIs('NAME')
                         ->regexIs('fullcode', '^(?i)(' . implode('|', $prefixes) . ').+?')
                         ->count()
                         ->raw('is(gte(' . $this->minimumFinds . '))')
                 );
            $this->prepareQuery();
        }
    }
}

?>
