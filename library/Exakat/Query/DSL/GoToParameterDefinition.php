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


namespace Exakat\Query\DSL;

class GoToParameterDefinition extends DSL {
    public function run(): Command {
        return new Command(<<<'GREMLIN'
has("rank")
.choose(
    has("rankName"), 

        __.sideEffect{ranked = it.get().value("rankName");}
        .repeat( __.in() ).until(hasLabel("Functioncall", "Newcall", "Methodcall", "Staticmethodcall"))
        .in("DEFINITION")
        .out("ARGUMENT")
        .where(__.out("NAME").filter{ it.get().value("fullcode") == ranked; }),

        // default behavior, rank + variadic
        __.sideEffect{ranked = it.get().value("rank");}
                     .repeat( __.in() ).until(hasLabel("Functioncall", "Newcall", "Methodcall", "Staticmethodcall"))
                     .in("DEFINITION")
                     .out("ARGUMENT")
                     .filter{ (it.get().value("rank") == ranked) || ("variadic" in it.get().keys() && it.get().value("rank") <= ranked); }
)


GREMLIN
);
    }
}

/*

*/
?>
