<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
    public function analyze() {

        $classes = $this->query('g.V().hasLabel("Class").out("NAME").groupCount("m").by("lccode").cap("m").next().keySet()');
        $interfaces = $this->query('g.V().hasLabel("Interface").out("NAME").groupCount("m").by("lccode").cap("m").next().keySet()');
        $traits = $this->query('g.V().hasLabel("Trait").out("NAME").groupCount("m").by("lccode").cap("m").next().keySet()');
        
        $names = array_merge($classes->toArray(), $interfaces->toArray(), $traits->toArray());
        $counts = array_count_values($names);
        $doubles = array_keys(array_filter($counts, function ($x) { return $x > 1; }));
        
        if (empty($doubles)) {
            return;
        }
        
        // Classes
        $this->atomIs('Class')
             ->outIs('NAME')
             ->codeIs($doubles, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();

        // Trait
        $this->atomIs('Trait')
             ->outIs('NAME')
             ->codeIs($doubles, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();

        // Interfaces
        $this->atomIs('Interface')
             ->outIs('NAME')
             ->codeIs($doubles, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
