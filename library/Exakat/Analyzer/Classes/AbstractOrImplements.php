<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class AbstractOrImplements extends Analyzer {
    public function analyze() {
        // an abstract parent method is not in the current children
        $this->atomIs('Class')
             ->raw(<<<GREMLIN
where(
__.sideEffect{ methods = []; 
            abstract_methods = [];
          }.or( __.not(has("abstract")), __.not(has("abstract", true))).where( out("EXTENDS") ).filter{true}.emit( ).repeat( __.as("x").out("EXTENDS", "IMPLEMENTS").in("DEFINITION").where(neq("x")) ).times(15).out("METHOD", "MAGICMETHOD")
.filter{
    if (it.get().properties("abstract").any()) {
        abstract_methods.add(it.get().vertices(OUT, 'NAME').next().value("code"));
    } else {
        methods.add(it.get().vertices(OUT, 'NAME').next().value("code"));
    }
}.fold()
.filter{ missing = abstract_methods - methods; missing != []; }
)
GREMLIN
);
        $this->prepareQuery();
    }
}

?>
