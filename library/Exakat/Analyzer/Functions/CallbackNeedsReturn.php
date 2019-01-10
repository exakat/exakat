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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class CallbackNeedsReturn extends Analyzer {
    public function analyze() {
        $ini = $this->loadIni('php_with_callback.ini');

        // array_map(function ($x) { }, $a)
        
        foreach($ini as $position => $functions) {
            $rank = substr($position, 9);
            if ($rank[0] === '_') {
                list(, $rank) = explode('_', $position);
            }
            
            //String callback
            $this->atomFunctionIs($functions)
                 ->outWithRank('ARGUMENT', $rank)
                 ->atomIsNot('Arrayliteral')
                 ->inIs('DEFINITION')
                 ->not(
                    $this->side()
                         ->filter(
                            $this->side()
                                 ->outIs('ARGUMENT')
                                 ->is('reference', true)
                         )
                 )
                 ->outIs('BLOCK')
                 ->noAtomInside('Return')
                 ->back('first');
            $this->prepareQuery();
    
            //Closure callback
            $this->atomFunctionIs($functions)
                 ->outWithRank('ARGUMENT', $rank)
                 ->atomIs('Closure')
                 ->not(
                    $this->side()
                         ->filter(
                            $this->side()
                                 ->outIs(array('ARGUMENT', 'USE'))
                                 ->is('reference', true)
                         )
                 )
                 ->outIs('BLOCK')
                 ->noAtomInside('Return')
                 ->back('first');
            $this->prepareQuery();

            //Static class callback
            $this->atomFunctionIs($functions)
                 ->outWithRank('ARGUMENT', $rank)
                 ->atomIs('Arrayliteral')
                 ->is('count', 2)
                 ->outWithRank('ARGUMENT', 1)
                 ->has('noDelimiter')
                 ->savePropertyAs('noDelimiter', 'method')
                 ->inIs('ARGUMENT')
                 ->outWithRank('ARGUMENT', 0)
                 ->inIs('DEFINITION')
                 ->outIs('METHOD')
                 ->is('static', true)
                 ->not(
                    $this->side()
                         ->filter(
                            $this->side()
                                 ->outIs('ARGUMENT')
                                 ->is('reference', true)
                         )
                 )
                 ->outIs('NAME')
                 ->samePropertyAs('fullcode', 'method')
                 ->inIs('NAME')
                 ->outIs('BLOCK')
                 ->noAtomInside('Return')
                 ->back('first');
            $this->prepareQuery();
    
            //Normal class callback
            // Still needs DEFINITION link
        }
    }
}

?>
