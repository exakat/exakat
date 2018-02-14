<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class MultipleIdenticalKeys extends Analyzer {

    public function analyze() {
        $this->atomIs('Arrayliteral')
             ->raw('where(
    __.sideEffect{ counts = [:]; }
      .out("ARGUMENT").hasLabel("Keyvalue").out("INDEX")
      .hasLabel("String", "Integer", "Real", "Boolean", "Null", "Staticconstant").not(where(__.out("CONCAT")) )
      .sideEffect{ 
            if (it.get().label() == "String" && "noDelimiter" in it.get().keys() ) { 
                k = it.get().value("noDelimiter"); 
                if (k.isInteger()) {
                    k = k.toInteger();
                }
            } 
            else if (it.get().label() == "Real" )           { k = it.get().value("intval"); } 
            else if (it.get().label() == "Staticconstant" ) { k = it.get().value("fullcode"); } 
            else if (it.get().label() == "Null" )           { k = 0; } 
            else if (it.get().label() == "Boolean" )        { 
                if (it.get().value("fullcode").toLowerCase() in ["\\false", "false"] ) { 
                    k = 0; 
                } else { 
                    k = 1; 
                } 
            } else { k = it.get().value("intval").toInteger(); }
            if (counts[k] == null) { 
                counts[k] = 1; 
            } else { 
                counts[k]++; 
            }
        }
        .map{ counts.findAll{it.value > 1}; }.unfold().count().is(neq(0))
)')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
