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


namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class MultipleIdenticalKeys extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze(): void {
        // array('a' => 1, 'b' = 2)
        $this->atomIs('Arrayliteral')
             ->isMore('count', 1)
            // first quick check to skip useless check later
             ->not(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->atomIsNot('Keyvalue')
             )
             ->filter(
                $this->side()
                     ->initVariable('counts', '[:]')
                     ->outIs('ARGUMENT')
                     ->atomIs('Keyvalue')
                     ->outIs('INDEX')
                     ->atomIs(array('String', 'Heredoc', 'Concatenation', 'Integer', 'Float', 'Boolean', 'Null', 'Staticclass'), self::WITH_CONSTANTS)
                     ->raw('or(has("intval"), has("noDelimiter"))')
                     ->raw(<<<'GREMLIN'
sideEffect{ 
    if (it.get().label() in ["String", "Heredoc", "Concatenation", "Staticclass"] ) { 
        k = it.get().value("noDelimiter"); 
        if (k.isInteger()) {
            k = k.toInteger();
            
            if (k.toString().length() != it.get().value("noDelimiter").length()) {
                k = it.get().value("noDelimiter"); 
            }
        }
    } 
    else { 
        k = it.get().value("intval"); 
    } 

    if (counts[k] == null) { 
        counts[k] = 1; 
    } else { 
        counts[k]++; 
    }
}

.filter{ counts.findAll{it.value > 1}.size() > 0; }
GREMLIN
)
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
