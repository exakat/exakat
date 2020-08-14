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


class OutWithRank extends DSL {
    public function run(): Command {
        assert(func_num_args() === 2, 'Wrong number of argument for ' . __METHOD__ . '. 2 are expected, ' . func_num_args() . ' provided');
        list($link, $rank) = func_get_args();

        if ($rank === 'first') {
            return new Command('out("' . $link . '").has("rank", eq(0))');
        } elseif ($rank === 'last') {
            return new Command('map( __.out("' . $link . '").order().by("rank").tail(1) )');
        } elseif ($rank === '2last') {
            return new Command('map( __.out("' . $link . '").order().by("rank").tail(2) )');
        } elseif (is_string($rank) && preg_match('/\D/', $rank)) {
            $this->assertVariable($rank, self::VARIABLE_READ);
            return new Command('out("' . $link . '").filter{ it.get().value("rank") == ' . $rank . '; }');
        } else { // abs((int) $rank) always works, and default to 0
            return new Command('out("' . $link . '").has("rank", eq(' . abs((int) $rank) . '))');
        }
    }
}
?>
