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

use Exakat\Query\Query;
use Exakat\Analyzer\Analyzer;

class AtomIsNot extends DSL {
    public function run(): Command {
        if (func_num_args() === 2) {
            list($atoms, $flags) = func_get_args();
        } else {
            list($atoms) = func_get_args();
            $flags = Analyzer::WITHOUT_CONSTANTS;
        }

        assert(func_num_args() <= 2, 'Too many arguments for ' . __METHOD__);
        $this->assertAtom($atoms);
        $diff = $this->normalizeAtoms($atoms);
        if (empty($diff)) {
            return new Command(Query::NO_QUERY);
        }

        if ($flags === Analyzer::WITH_CONSTANTS) {
            // Ternary are unsupported
            // arrays, members, static members are not supported
            $gremlin = <<<'GREMLIN'
coalesce( __.hasLabel(within(["Identifier", "Nsname", "Staticconstant"])).in("DEFINITION").out("VALUE"),
            // Local constant
          __.hasLabel(within(["Variable"])).in("DEFINITION").out("DEFAULT"),

          // local variable
          __.hasLabel(within(["Variable"])).in("DEFINITION").hasLabel('Variabledefinition', 'Staticdefinition').out("DEFAULT"),
          
          // literal value, passed as an argument (Method, closure, function)
          __.hasLabel(within(["Variable"])).in("DEFINITION").in("NAME").hasLabel('Parameter').sideEffect{ rank = it.get().value('rank');}.in("ARGUMENT").out("DEFINITION").optional(__.out("METHOD")).out("ARGUMENT").filter{ rank == it.get().value('rank');},

          // literal value, passed as an argument
          __.hasLabel(within(["Ternary"])).out("THEN", "ELSE").not(hasLabel('Void')),

          __.hasLabel(within(["Coalesce"])).out("LEFT", "RIGHT"),
          
          // default case, will be filtered by hasLabel()
          __.filter{true})
.not(hasLabel(within(***)))
GREMLIN;
            return new Command($gremlin, array($diff));
        } else {
            // WITHOUT_CONSTANTS or non-constant atoms
            return new Command('not(hasLabel(within(***)))', array($diff));
        }
    }
}
?>
