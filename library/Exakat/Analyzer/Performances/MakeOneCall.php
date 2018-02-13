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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class MakeOneCall extends Analyzer {
    public function analyze() {
        // the second argument must match between calls
        $functionsArg2 = array('\\str_replace',
                               '\\str_ireplace',
                               '\\preg_replace_callback',
                               '\\preg_replace',
                              );
        
        // preg_replace( **, **, x); called several times
        // str_replace( **, **, x); called several times
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomFunctionIs($functionsArg2)
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('LEFT')
             ->atomIs(self::$CONTAINERS)
             ->savePropertyAs('fullcode', 'string')
             ->back('first')
             ->nextSibling()
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->functioncallIs($functionsArg2)
             ->samePropertyAs('fullnspath', 'fnp')
             ->outWithRank('ARGUMENT', 2)
             ->samePropertyAs('fullcode', 'string')
             ->back('first');
        $this->prepareQuery();

        // Nesting str_replace calls
        // First level calling
        $this->atomFunctionIs($functionsArg2)
             ->hasIn('ARGUMENT')
             ->savePropertyAs('fullnspath', 'function')
             ->inIs('ARGUMENT')
             ->atomIs('Functioncall')
             ->has('fullnspath')
             ->samePropertyAs('fullnspath', 'function')
             ->hasNoIn('ARGUMENT');
        $this->prepareQuery();

        //Calling from another functioncall
        // Second level calling
        $this->atomFunctionIs($functionsArg2)
             ->hasIn('ARGUMENT')
             ->savePropertyAs('fullnspath', 'function')
             ->inIs('ARGUMENT')
             ->atomIs('Functioncall')
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'function')
             ->inIs('ARGUMENT')
             ->atomIs('Functioncall')
             ->has('fullnspath')
             ->samePropertyAs('fullnspath', 'function')
             ->hasIn('ARGUMENT');
        $this->prepareQuery();

        // same functions, in a foreach?
    }
}

?>
