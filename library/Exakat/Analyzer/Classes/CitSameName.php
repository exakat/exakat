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

class CitSameName extends Analyzer {
    public function analyze(): void {

        $this->atomIs(self::CLASSES_ALL)
             ->outIs('NAME')
             ->values('lccode')
             ->unique();
        $classes = $this->rawQuery();

        $this->atomIs('Interface')
             ->outIs('NAME')
             ->values('lccode')
             ->unique();
        $interfaces = $this->rawQuery();

        $this->atomIs('Trait')
             ->outIs('NAME')
             ->values('lccode')
             ->unique();
        $traits = $this->rawQuery();

        $names = array_merge($classes->toArray(), $interfaces->toArray(), $traits->toArray());
        $counts = array_count_values($names);
        $doubles = array_keys(array_filter($counts, function ($x) { return $x > 1; }));

        if (empty($doubles)) {
            return;
        }

        // Classes, traits, interfaces
        $this->atomIs(self::CIT)
             ->outIs('NAME')
             ->codeIs($doubles, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
