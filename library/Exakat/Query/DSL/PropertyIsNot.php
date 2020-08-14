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
use Exakat\Analyzer\Analyzer;

class PropertyIsNot extends DSL {
    public function run(): Command {
        list($property, $code, $caseSensitive) = func_get_args();

        assert($this->assertProperty($property));

        if (is_array($code) && empty($code) ) {
            return new Command(Query::NO_QUERY);
        }

        if ($caseSensitive === Analyzer::CASE_SENSITIVE) {
            $caseSensitive = '';
        } elseif ($caseSensitive === Analyzer::CASE_INSENSITIVE) {
            $code = $this->tolowercase($code);
            $caseSensitive = '.toString().toLowerCase()';
        } else {
            assert(false, 'No such case sensitivity : "' . $caseSensitive . '"');
        }

        if (is_array($code) && !empty(array_intersect($code, $this->availableVariables))) {
            return new Command('filter{it.get().value("' . $property . '")' . $caseSensitive . ' != ' . $code[0] . '}', array());
        } elseif (is_string($code) && in_array($code, $this->availableVariables)) {
            return new Command(<<<GREMLIN
filter{
    if ($code instanceof java.util.List) {
        !(it.get().value("$property")$caseSensitive in $code);
    } else {
        it.get().value("$property")$caseSensitive != $code;
    }
}
GREMLIN
, array());
        } elseif (is_array($code)) {
            return new Command('filter{ !(it.get().value("' . $property . '")' . $caseSensitive . ' in ***); }', array($code));
        } else {
            return new Command('filter{it.get().value("' . $property . '")' . $caseSensitive . ' != ***}', array($code));
        }
    }
}
?>
