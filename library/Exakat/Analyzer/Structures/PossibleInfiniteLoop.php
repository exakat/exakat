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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class PossibleInfiniteLoop extends Analyzer {
    public function analyze() {
        $readFunctions = array('\fgets', 
                               '\fgetc', 
                               '\fgetss', 
                               '\fgetcsv', 
                              );
        
        //while($line = fgets($fp1) != 'a') {}
        $this->atomIs('Dowhile')
             ->outIs('CONDITION')
             ->outIsIE(array('LEFT', 'RIGHT')) // outIsIE goes directly to the comparison operands
             ->functioncallIs($readFunctions)
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->codeIs('!=')
             ->outIs(array('LEFT', 'RIGHT'))
             ->isLiteral()
             ->is('boolean', true)
             ->back('first');
        $this->prepareQuery();

        //while($line = fgets($fp1) != 'a') {}
        $this->atomIs('While')
             ->outIs('CONDITION')
             ->outIsIE(array('LEFT', 'RIGHT')) // outIsIE goes directly to the comparison operands
             ->functioncallIs($readFunctions)
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->codeIs('!=')
             ->outIs(array('LEFT', 'RIGHT'))
             ->isLiteral()
             ->is('boolean', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
