<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class EchoPrintConsistance extends Analyzer {

    public function analyze() {
        
        $inconsistent = $this->query(<<<GREMLIN
g.V().as("first").hasLabel("Functioncall")
                 .where( __.in("METHOD", "NEW").count().is(eq(0)) )
                 .has("token", within("T_ECHO", "T_PRINT"))
                 .groupCount("gf").by{it.value("fullnspath");}.cap("gf").sideEffect{ s = it.get().values().sum(); }.next().findAll{ it.value < s / 10;}.keySet()
GREMLIN
);

        if (!empty($inconsistent)) {
            $this->atomFunctionIs(array('\echo', '\print'))
                 ->fullnspathIs($inconsistent);
            $this->prepareQuery();
        }
    }
}

?>
