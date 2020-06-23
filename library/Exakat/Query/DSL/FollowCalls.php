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


class FollowCalls extends DSL {
    public function run(): Command {

        $TIME_LIMIT = self::$TIME_LIMIT;

        switch(func_num_args()) {
            case 1:
                $loopings = (int) func_get_arg(0);
                break;

           default:
                $loopings = self::$MAX_LOOPING;
                break;
        }

        // Coalesce is not supported
        return new Command(<<<GREMLIN
emit().repeat(
    __.timeLimit($TIME_LIMIT).out("NAME").out("DEFINITION")
      .union(__.identity(),
            // local assignation to variable
            __.emit().repeat(
                 __.in("DEFAULT").hasLabel("Variabledefinition").out("DEFINITION")
             ).times(4)
      )
      .as("a").in("ARGUMENT").in("DEFINITION").out("ARGUMENT").as("b")
      .where("a", eq("b") ).by("rank")
).times($loopings)
GREMLIN
);
    }
}
?>
