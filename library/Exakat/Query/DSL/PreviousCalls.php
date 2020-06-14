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

class PreviousCalls extends DSL {
    public function run(): Command {

        if(func_num_args() === 1) {
            $times = abs((int) func_get_arg(0));
        } else {
            $times = 1;
        }
        // Starting from Parameter, going to next parameter
        // Need a number of executions?

        if ($times === 0) {
            return new Command(Query::NO_QUERY);
        } else {
            $TIME_LIMIT = self::$TIME_LIMIT;

            return new Command(<<<GREMLIN
emit().repeat(
     __
     .timeLimit($TIME_LIMIT)
     .sideEffect{ ranked = it.get().value('rank');}
     .in('ARGUMENT')
     .out('DEFINITION')
     .out('ARGUMENT')
     .filter{ it.get().value('rank') == ranked;}
     .in('DEFINITION')
     .in("NAME")

).times($times)
GREMLIN
);
        }
    }
}
?>
