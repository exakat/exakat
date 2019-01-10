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


namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class AmbiguousKeys extends Analyzer {

    public function analyze() {
        $this->atomIs('Arrayliteral')
             ->raw(<<<GREMLIN
where(
    __.sideEffect{ counts = [:]; integers = [:]; strings = [:]; }
      .out("ARGUMENT").hasLabel("Keyvalue").out("INDEX")
      .hasLabel("String", "Integer").not( where(__.out("CONCAT") ) )
      .filter{ 
            if (it.get().label() == "String" && "noDelimiter" in it.get().keys()) { 
                k = it.get().value("noDelimiter"); 
                if (counts[k] == null) { 
                    counts[k] = ["string"]; 
                    false;
                } else if (counts[k] == ["integer"]) { 
                    true;
                } else { 
                    false;
                }
            } else { 
                k = it.get().value("fullcode"); 
                if (counts[k] == null) { 
                    counts[k] = ["integer"]; 
                    false;
                } else if (counts[k] == ["string"]) { 
                    counts[k].add("string"); 
                    true;
                } else { 
                    false;
                }
            }
        }
)
GREMLIN
);
        $this->prepareQuery();

        // $x = [1.0 => 2];
        $this->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->outIs('INDEX')
             ->atomIs(array('Real', 'Null', 'Boolean'))
             ->back('first');
        $this->prepareQuery();

        // $x[1.0] = 2;
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs(array('Real', 'Null', 'Boolean'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
