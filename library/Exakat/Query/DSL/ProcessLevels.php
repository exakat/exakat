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
            $filter = ".filter{ levels > $maxLevel}";
        } else {
            $filter = '';
        }

        $MAX_LOOPING = self::$MAX_LOOPING;

        $command = new Command(<<<GREMLIN
local(__.sideEffect{levels=0;}
        .emit().repeat( __.sideEffect{levels += (it.get().property("token") != "T_ELSEIF" && ["Ifthen", "While", "Dowhile", "For", "Foreach", "Switch"].contains(it.get().label())) ? 1 : 0;}
               .out().not(hasLabel("Closure", "Arrowfunction", "Function", "Class", "Classanonymous", "Trait", "Interface")) ).times($MAX_LOOPING)
        $filter
)

GREMLIN
);

        return $command;
    }
}
?>
