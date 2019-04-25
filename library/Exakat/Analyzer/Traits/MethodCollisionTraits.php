<?php
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

namespace Exakat\Analyzer\Traits;

use Exakat\Analyzer\Analyzer;

class MethodCollisionTraits extends Analyzer {
    public function analyze() {
        $this->atomIs('Class')
             ->raw(<<<'GREMLIN'
 where(
  __.sideEffect{ methods = []; insteadof = [];}
    .out("USE")
    .hasLabel("Usetrait")
    .where(
      __.out("BLOCK")
        .out("EXPRESSION")
        .out("NAME")
        .out("METHOD")
        .sideEffect{ insteadof.add(it.get().value("lccode"));}
        .fold()
    )
    .out("USE")
    .in("DEFINITION")
    .hasLabel("Trait")
    .dedup()
    .out("METHOD", "MAGICMETHOD")
    .out("NAME")
    .sideEffect{ methods.add(it.get().value("lccode"));}
    .fold()
)
.filter{ 
    collisions = methods - insteadof; 
    collisions.countBy{it}.findAll{k,v -> v > 1;} != [:];
}

GREMLIN
);
        $this->prepareQuery();
    }
}

?>
