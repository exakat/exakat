<?php
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

class SavePropertyAs extends DSL {
    public function run() {
        assert(func_num_args() === 2, __METHOD__ . ' should get 2 arguments');
        list($property, $name) = func_get_args();

        $this->assertVariable($name, self::VARIABLE_WRITE);
        $this->assertProperty($property);

        if ($property === 'label') {
            return new Command('sideEffect{ ' . $name . ' = it.get().label(); }');
        } elseif ($property === 'id') {
            return new Command('sideEffect{ ' . $name . ' = it.get().id(); }');
        } elseif ($property === 'self') {
            return new Command('sideEffect{ ' . $name . ' = it.get(); }');
        } elseif (in_array($property, self::BOOLEAN_PROPERTY, \STRICT_COMPARISON)) {
            return new Command('sideEffect{ if ( it.get().properties("' . $property . '").any()) { ' . $name . ' = it.get().value("' . $property . '")} else {' . $name . ' = false; }; }');
        } else {
            return new Command('has("' . $property . '").sideEffect{ ' . $name . ' = it.get().value("' . $property . '"); }');
        }
    }
}
?>
