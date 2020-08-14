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

use Exakat\Exceptions\QueryException;

class IsNot extends DSL {
    public function run(): Command {
        assert(func_num_args() === 2, 'Wrong number of argument for ' . __METHOD__ . '. 2 are expected, ' . func_num_args() . ' provided');
        list($property, $value) = func_get_args();

        $this->assertProperty($property);

        if ($value === null) {
            return new Command("has(\"$property\").or( __.not(has(\"$property\")), __.not(has(\"$property\", null)))");
        } elseif (in_array($property, self::BOOLEAN_PROPERTY, \STRICT_COMPARISON)) {
            $value = $value === true ? 'true' : 'false';

            return new Command('filter{ if ( it.get().properties("' . $property . '").any()) { ' . $value . ' != it.get().value("' . $property . '")} else {' . $value . ' != false; }; }');
        } elseif ($value === true) {
            return new Command("has(\"$property\", false)");
        } elseif ($value === false) {
            return new Command("has(\"$property\", true)");
        } elseif (is_int($value)) {
            return new Command("has(\"$property\").not(has(\"$property\", ***))", array($value));
        } elseif (is_string($value)) {
            if (empty($value)) {
                return new Command("has(\"$property\").not(has(\"$property\", ''))");
            } else {
                return new Command("has(\"$property\").not(has(\"$property\", ***))", array($value));
            }
        } elseif (is_array($value)) {
            if (empty($value)) {
                return new Command("has(\"$property\").not(has(\"$property\", ''))");
            } else {
                return new Command("has(\"$property\").not(has(\"$property\", within(***)))", array($value));
            }
        } else {
            throw new QueryException('Not understood type for isNot : ' . gettype($value));
        }
    }
}
?>
