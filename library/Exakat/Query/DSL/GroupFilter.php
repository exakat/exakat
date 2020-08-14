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


class GroupFilter extends DSL {
    public function run(): Command {
        assert(func_num_args() === 2, 'Wrong number of argument for ' . __METHOD__ . '. 2 are expected, ' . func_num_args() . ' provided');
        list($characteristic, $percentage) = func_get_args();

        if (substr(trim($characteristic), 0, 3) === 'it.') {
            $by = "by{ $characteristic }";
        } else {
            $by = "by{ \"$characteristic\" }";
        }

        $gremlin = <<<GREMLIN
groupCount("gf").$by.cap("gf").sideEffect{ s = it.get().values().sum(); }.next().findAll{ it.value < s * $percentage; }.keySet()
GREMLIN;
        return new Command($gremlin);
    }
}
?>
