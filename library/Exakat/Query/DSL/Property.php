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


class Property extends DSL {
    public function run(): Command {
        list($property, $value) = func_get_args();

        assert($this->assertProperty($property));

        // special case for boolean
        if (is_bool($value)) {
            return new Command('sideEffect{ it.get().property("' . $property . '", ' . ($value === true ? 'true' : 'false') . '); }', array());
        } elseif (is_int($value)) {
            return new Command('sideEffect{ it.get().property("' . $property . '", ' . $value . '); }', array());
        } else {
            assert($this->assertVariable($value, self::VARIABLE_READ), "$value is not a variable");
            // Default, a gremlin variable
            return new Command('sideEffect{ it.get().property("' . $property . '", ' . $value . '); }', array());
        }
    }
}
?>
