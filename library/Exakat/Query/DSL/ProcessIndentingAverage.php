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


class ProcessIndentingAverage extends DSL {
    public function run(): Command {
        assert(func_num_args() === 2, 'Wrong number of argument for ' . __METHOD__ . '. 2 is expected, ' . func_num_args() . ' provided');

        $indentationThreshold = abs((float) func_get_arg(0));
        $minimumSize          = abs((int) func_get_arg(1));

        if ($indentationThreshold === 0) {
            return self::NO_QUERY;
        }

        $MAX_LOOPING = self::$MAX_LOOPING;

        // round() is used for lone blocks in the code
        // it may be excessive
        $command = new Command(<<<GREMLIN
where(
    __.sideEffect{ levels = []; }
      .repeat( __.out("BLOCK", "EXPRESSION", "THEN", "ELSE", "CASES")).emit().times($MAX_LOOPING)
      .not(hasLabel("Sequence", "Block", "Void"))
      .path()
      .sideEffect{ levels.add(Math.round((it.get().size() - 1 ) / 2 - 1)); }
      .fold()
      .filter{ levels != [];}
).filter{ levels.size() >= $minimumSize && levels.sum() / levels.size() >= $indentationThreshold}
GREMLIN
);

        return $command;
    }
}

/* debugging purposes
.sideEffect{ name = it.get().value('fullcode'); }
.where(
    __.sideEffect{ levels = []; fullcodes = [];}
      .repeat( __.out('BLOCK', 'EXPRESSION', 'THEN', 'ELSE', 'CASES')).emit().times(100)
      .not(hasLabel('Sequence', 'Block', "Void"))
      .sideEffect{ fullcodes.add(it.get().value('fullcode'));}
      .path()
      .sideEffect{ levels.add(Math.round((it.get().size() - 1 ) / 2 - 1)); }
      .fold()
)
//.filter{ levels.sum() / levels.size() > 1}
.map{['name':name, 'levels':levels, 'average':levels.sum() / levels.size(), 'max':levels.max(), 'fullcode':fullcodes ];}

*/
?>
