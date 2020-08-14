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

class RegexIsNot extends DSL {
    public function run(): Command {
        assert(func_num_args() === 2, 'Wrong number of argument for ' . __METHOD__ . '. 2 are expected, ' . func_num_args() . ' provided');
        list($property, $regex) = func_get_args();

        $this->assertProperty($property);

        if ($property === 'code') {
            $values = $this->dictCode->grep($regex);

            if (empty($values)) {
                return new Command(Query::NO_QUERY);
            }

            return new Command('not( has("code", within(***) ) )', array($values));
        }

        return new Command(<<<GREMLIN
has("$property")
.filter{ !(it.get().value("$property") =~ "$regex" ).asBoolean() }
GREMLIN
                          );
    }
}
?>
