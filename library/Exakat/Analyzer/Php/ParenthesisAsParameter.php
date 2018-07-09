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


namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

class ParenthesisAsParameter extends Analyzer {
    public function analyze() {
        // foo( (1 + 2), 3, (new x))
        // Only valid if the argument is a reference
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENT')
             ->atomIs('Parenthesis')
             ->savePropertyAs('rank', 'rank')
             ->back('first')
             ->inIs('DEFINITION')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')
             ->is('reference', true)
             ->back('first');
        $this->prepareQuery();

        // PHP functions that are references
        $data = new Methods($this->config);
        
        $functions = $data->getFunctionsReferenceArgs();
        $references = array();
        
        foreach($functions as $function) {
            if (isset($references[$function['position']])) {
                $references[$function['position']][] = '\\'.$function['function'];
            } else {
                $references[$function['position']] = array('\\'.$function['function']);
            }
        }
        
        foreach($references as $position => $functions) {
            $this->atomFunctionIs($functions)
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs('Parenthesis')
                 ->back('first');
            $this->prepareQuery();
        }

    }
}

?>
