<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Files;

use Exakat\Analyzer\Analyzer;

class GlobalCodeOnly extends Analyzer {
    public function analyze() {
        $definitionsFunctionsList = "\"\\\\".join("\", \"\\\\", DefinitionsOnly::$definitionsFunctions)."\"";
        $definitionsList = "\"".join("\", \"", DefinitionsOnly::$definitions)."\"";

        // one or several namespaces
        $this->atomIs('File')
             ->outIs('FILE')
             ->outIs('EXPRESSION')
             ->outIs('CODE')
             ->raw('coalesce( __.out("EXPRESSION").hasLabel("Namespace").out("BLOCK"), __.filter{ true; } )')
             ->raw('where( __.out("EXPRESSION").hasLabel('.$definitionsList.').count().is(eq(0)) )')
             ->raw('where( __.hasLabel("Function").where( __.out("NAME").hasLabel("Void").count().is(eq(0))).count().is(eq(0)) )')
             ->raw('where( __.in("ANALYZED").not(has("analyzer", "Structures/NoDirectAccess") ).count().is(eq(0)) )')
             ->raw('where( __.hasLabel("Functioncall").filter{ it.get().value("fullnspath") in ['.$definitionsFunctionsList.'] }.count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
