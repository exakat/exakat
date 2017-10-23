<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class ClassFunctionConfusion extends Analyzer {
    public function analyze() {
        // class foo{}; function foo();
        // possible confusion
        $functions = $this->query('g.V().hasLabel("Function")
                                        .values("fullnspath").unique()');

        $classes = $this->query('g.V().hasLabel("Class", "Interface", "Trait")
                                        .values("fullnspath").unique()');

        $common = array_intersect($functions, $classes);

        if (empty($common)) {
            return;
        }
        $this->atomIs(array('Class', 'Trait', 'Interface'))
             ->fullnspathIs($common);
        $this->prepareQuery();

        $this->atomIs('Function')
             ->hasNoClassInterfaceTrait()
             ->fullnspathIs($common);
        $this->prepareQuery();
    }
}

?>
