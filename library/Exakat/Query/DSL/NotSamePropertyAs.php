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

use Exakat\Analyzer\Analyzer;

class NotSamePropertyAs extends DSL {
    public function run(): Command {
        if (func_num_args() === 2) {
            list($property, $name) = func_get_args();
            $caseSensitive = Analyzer::CASE_SENSITIVE;
        } else {
            list($property, $name, $caseSensitive) = func_get_args();
        }

        assert($this->assertProperty($property));
        assert($this->assertVariable($name));

        switch ($property) {
            case 'label':
                return new Command("filter{ it.get().label() != $name }");

            case 'id':
                return new Command("filter{ it.get().id() != $name }");

            case 'self':
                return new Command("filter{ it.get() != $name }");

            case 'reference':
                return new Command('filter{ if (it.get().properties("reference").any()) { ' . $name . ' != it.get().value("reference");} else { ' . $name . ' != false; }}');

            default :
                if (in_array($property, self::BOOLEAN_PROPERTY, \STRICT_COMPARISON)) {
                    return new Command('filter{ if ( it.get().properties("' . $property . '").any()) { ' . $name . ' != it.get().value("' . $property . '")} else {' . $name . ' != false; }; }');
                } elseif (in_array($property, self::INTEGER_PROPERTY, \STRICT_COMPARISON)) {
                    return new Command("has(\"$property\").filter{ it.get().value(\"$property\") != $name}");
                } else {
                    if ($caseSensitive === Analyzer::CASE_SENSITIVE) {
                        $caseSensitive = '';
                    } else {
                        $caseSensitive = '.toLowerCase()';
                    }

                    return new Command("has(\"$property\").filter{ it.get().value(\"$property\")$caseSensitive != $name$caseSensitive}");
                }
        }
    }
}
?>
