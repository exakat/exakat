<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Interfaces;

use Analyzer;

class UsedInterfaces extends Analyzer\Analyzer {
    public function analyze() {
        // interface used in a class definition
        $classes = $this->query('g.V().hasLabel("Class", "Interface").out("IMPLEMENTS", "EXTENDS").values("fullnspath").unique()');
        $instanceof = $this->query('g.V().hasLabel("Instanceof").out("CLASS").values("fullnspath").unique()');
        $typehints = $this->query('g.V().hasLabel("Function").out("ARGUMENTS").out("ARGUMENT").out("TYPEHINT").values("fullnspath").unique()');

        $all = array_merge($classes, $instanceof, $typehints);

        $this->atomIs('Interface')
             ->fullnspathIs($all);
        $this->prepareQuery();
    }
}

?>
