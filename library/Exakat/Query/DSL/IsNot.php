<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Exceptions\QueryException;

class IsNot extends DSL {
    protected $args = array('atom');

    public function run() {
        list($property, $value) = func_get_args();

        assert($this->assertProperty($property));
        if ($value === null) {
            return new Command("has(\"$property\").or( __.not(has(\"$property\")), __.not(has(\"$property\", null)))");
        } elseif ($value === true) {
            return new Command("or( __.not(has(\"$property\")), __.not(has(\"$property\", true)))");
        } elseif ($value === false) {
            return new Command("has(\"$property\").or( __.not(has(\"$property\")), __.not(has(\"$property\", false)))");
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
            throw new QueryException('Not understood type for isNot : '.gettype($value));
        }
    }
}
?>
