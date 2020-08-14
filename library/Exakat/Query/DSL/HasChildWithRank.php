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

class HasChildWithRank extends DSL {
    public function run(): Command {
        if (func_num_args() === 2) {
            list($links, $rank) = func_get_args();
        } else {
            list($links) = func_get_args();
            $rank = 0;
        }

        $this->assertLink($links);
        $diff = $this->normalizeLinks($links);
        if (empty($diff)) {
            return new Command(Query::STOP_QUERY);
        }

        return new Command('where( __.out(' . $this->SorA($diff) . ').has("rank", ***).not(hasLabel("Void")) )', array(abs((int) $rank)));
    }
}
?>
