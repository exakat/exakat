<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class GetStringLength extends DSL {
    public function run() {
        list($property, $variable) = func_get_args();

        $gremlin = <<<'GREMLIN'
sideEffect{
    s = it.get().value("PROPERTY");
    
    // Replace all special chars by a single char
    s = s.replaceAll(/\\[\\aefnRrt]/, "A");
    s = s.replaceAll(/\\0\d\d/, "A");
    s = s.replaceAll(/\\u\{[^\}]+\}/, "A");
    s = s.replaceAll(/\\[pP]\{^?[A-Z][a-z]?\}/, "A");
    s = s.replaceAll(/\\[pP][A-Z]/, "A");
    s = s.replaceAll(/\\X[A-Z][a-z]/, "A");
    s = s.replaceAll(/\\x[a-fA-F0-9]{2}/, "A");

    VARIABLE = s.length();
}

GREMLIN;

        $gremlin = str_replace(array('PROPERTY', 'VARIABLE'), array($property, $variable), $gremlin);

        return new Command($gremlin);
    }
}
?>
