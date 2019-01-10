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

class FetchContext extends DSL {
    public function run() {
        $linksDown = self::$linksDown;
        $gremlin = <<<GREMLIN
as("context")
.sideEffect{ line = it.get().value("line");
             fullcode = it.get().value("fullcode");
             file="None"; 
             theFunction = "None"; 
             theClass="None"; 
             theNamespace="\\\\"; 
             }
.sideEffect{ line = it.get().value("line"); }
.until( hasLabel("File") ).repeat( 
    __.in($linksDown)
      .sideEffect{ if (it.get().label() == "Function") { theFunction = it.get().value("code")} }
      .sideEffect{ if (it.get().label() in ["Class"]) { theClass = it.get().value("fullcode")} }
      .sideEffect{ if (it.get().label() in ["Namespace"]) { theNamespace = it.get().vertices(OUT, "NAME").next().value("fullcode")} }
       )
.sideEffect{  file = it.get().value("fullcode");}
.sideEffect{ context = ["line":line, 
                        "file":file, 
                        "fullcode":fullcode, 
                        "function":theFunction, 
                        "class":theClass, 
                        "namespace":theNamespace]; }
.select("context")

GREMLIN;

        return new Command($gremlin);
    }
}
?>
