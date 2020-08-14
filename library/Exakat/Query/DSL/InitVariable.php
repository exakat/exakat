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


class InitVariable extends DSL {
    public function run(): Command {
        if (func_num_args() === 2) {
            list($name, $value) = func_get_args();
        } else {
            list($name) = func_get_args();
            $value = '[]';
        }


        if (is_string($name)) {
            // Value should not be a direct groovy code!!!
            $this->assertVariable($name, self::VARIABLE_WRITE);
            return new Command('sideEffect{ ' . $name . ' = ' . $value . ' }');
        } elseif (is_array($name) && is_array($value)) {
            $value = array_values($value);
            $gremlin = array();

            foreach(array_values($name) as $id => $n) {
                // Value should not be a direct groovy code!!!
                $this->assertVariable($n, self::VARIABLE_WRITE);
                $gremlin[] = "$n  =  {$value[$id]};";
            }
            return new Command('sideEffect{ ' . implode(PHP_EOL, $gremlin) . ' }');
        } else {
            assert(false, 'Wrong format for ' . __METHOD__ . '. Either string/value or array()/array()');
        }
    }
}
?>
