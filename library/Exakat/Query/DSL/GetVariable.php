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


class GetVariable extends DSL {
    public function run(): Command {
        // getVariable($variable => $name of the variable)
        if (func_num_args() === 1) {
            list($variable) = func_get_args();
            $name = $variable;
        } else {
            list($variable, $name) = func_get_args();
        }

        if (is_string($variable) && is_string($name)) {
            // Value should not be a direct groovy code!!!
            $this->assertVariable($variable, self::VARIABLE_READ);
            return new Command('map{ [' . $name . ':' . $variable . ']; }');
        } elseif (is_array($variable) && is_array($name)) {
            $name = array_values($name);
            $gremlin = array();

            foreach(array_values($variable) as $id => $v) {
                // Value should not be a direct groovy code!!!
                $this->assertVariable($v, self::VARIABLE_READ);
                $gremlin[] = "\"{$name[$id]}\" :  $v";
            }
            return new Command('map{ [' . implode(',' . PHP_EOL, $gremlin) . '] }');
        } else {
            assert(false, 'Wrong format for ' . __METHOD__ . '. Either string/value or array()/array()');
        }
    }
}
?>
