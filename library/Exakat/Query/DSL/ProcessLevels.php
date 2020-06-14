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


class ProcessLevels extends DSL {
    public function run(): Command {
        if (func_num_args() === 1) {
            list($maxLevel) = func_get_args();
            $filter = ".filter{ levels.max() > $maxLevel}";
        } else {
            $filter = 'map{levels.max();}';
        }

        $MAX_LOOPING = self::$MAX_LOOPING;

        // round() is used for lone blocks in the code
        // it may be excessive
        $command = new Command(<<<GREMLIN
where(
    __.sideEffect{ levels = []; }
      .repeat( __.out('BLOCK', 'EXPRESSION', 'THEN', 'ELSE', 'CASES')).emit().times($MAX_LOOPING)
      .not(hasLabel('Sequence', 'Block'))
      .path()
      .sideEffect{ levels.add(Math.round((it.get().size() - 1 ) / 2 - 1));}
      .count()
)$filter
GREMLIN
);

        return $command;
    }
}
?>
