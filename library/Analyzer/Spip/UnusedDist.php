<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Spip;

use Analyzer;

class UnusedDist extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->outIs('NAME')
             ->regex('code', '_dist\\$')
             ->savePropertyAs('code', 'name')
             ->raw(<<<GREMLIN
filter{ g.idx("atoms")[["atom":"Functioncall"]].has("fullnspath", "\\\\charger_fonction")
                                               .sideEffect{
                                                    fonction = it.out("ARGUMENTS").out("ARGUMENT").has('token', 'T_CONSTANT_ENCAPSED_STRING').has("rank", 0).next().noDelimiter.replace("/", "_").toLowerCase();
                                                    sub = "exec";
                                                    if (it.out("ARGUMENTS").out("ARGUMENT").has('token', 'T_CONSTANT_ENCAPSED_STRING').has("rank", 1).any()) {
                                                        sub = it.out("ARGUMENTS").out("ARGUMENT").has('token', 'T_CONSTANT_ENCAPSED_STRING').has("rank", 1).next().noDelimiter.toLowerCase();
                                                    }
                                                }
                                               .filter{ name.toLowerCase() == sub + "_" + fonction + "_dist" }
                                               .any() == false
}

GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
