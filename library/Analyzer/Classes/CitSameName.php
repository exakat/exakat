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


namespace Analyzer\Classes;

use Analyzer;

class CitSameName extends Analyzer\Analyzer {
    public function analyze() {
        // Classes - Interfaces
        $this->atomIs('Class')
             ->outIs('NAME')
             ->raw('filter{ g.idx("atoms")[["atom":"Interface"]].out("NAME").next().code == it.code}')
             ->back('first');
        $this->prepareQuery();

        // Classes - Traits
        $this->atomIs('Class')
             ->analyzerIsNot('Analyzer\\Classes\\CitSameName')
             ->outIs('NAME')
             ->raw('filter{ g.idx("atoms")[["atom":"Trait"]].out("NAME").next().code == it.code}')
             ->back('first');
        $this->prepareQuery();

        // Interfaces - Traits
        $this->atomIs("Interface")
             ->outIs('NAME')
             ->raw('filter{ g.idx("atoms")[["atom":"Trait"]].out("NAME").next().code == it.code}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
