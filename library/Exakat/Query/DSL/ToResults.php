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

class ToResults extends DSL {
    public function run(): Command {
        $linksDown = self::$linksDown;
        return new Command(<<<GREMLIN
sideEffect{ line = it.get().value("line");
             fullcode = it.get().value("fullcode");
             file = "None"; 
             theFunction = ""; 
             theClass = ""; 
             theNamespace = ""; 
             }
.where( __.until( hasLabel("Project") ).repeat( 
    __.in({$linksDown})
      .sideEffect{ if (theFunction == "" && it.get().label() in ["Function", "Closure", "Arrayfunction", "Magicmethod", "Method"]) { theFunction = it.get().value("fullcode")} }
      .sideEffect{ if (theClass == ""    && it.get().label() in ["Class", "Trait", "Interface", "Classanonymous"]                ) { theClass = it.get().value("fullcode")   } }
      .sideEffect{ if (it.get().label() == "File") { file = it.get().value("fullcode")} }
       ).fold()
)
.map{ ["fullcode":fullcode, 
       "file":file, 
       "line":line, 
       "namespace":theNamespace, 
       "class":theClass, 
       "function":theFunction,
       "analyzer":"xxxx"];}
GREMLIN
);
    }
}
?>
