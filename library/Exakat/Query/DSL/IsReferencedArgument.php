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


class IsReferencedArgument extends DSL {
    public function run(): Command {
        switch (func_num_args()) {
            case 1:
                list($variable) = func_get_args();
                break;

            case 0:
                $variable = 'variable';
                break;
        }

        $gremlin = <<<GREMLIN
not(
    where(
        __.repeat( __.in()).until(hasLabel("Function")).out("ARGUMENT").filter{it.get().value("code") == $variable}.has("reference", true)
    )
)

GREMLIN;

        return new Command($gremlin);
    }
}
?>
