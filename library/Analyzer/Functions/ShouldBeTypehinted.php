<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Functions;

use Analyzer;

class ShouldBeTypehinted extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Arguments');
    }
    
    public function analyze() {
        // spotting objects with property
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->isNot('lambda', 'true')
             ->outIs('BLOCK')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // spotting objects with methodcall
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->isNot('lambda', 'true')
             ->outIs('BLOCK')
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();


        // spotting array with array[index]
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->isNot('lambda', 'true')
             ->outIs('BLOCK')
             ->atomInside('Array')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // spotting array with arrayappend[]
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->isNot('lambda', 'true')
             ->outIs('BLOCK')
             ->atomInside('Arrayappend')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // spotting array in a functioncall
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->isNot('lambda', 'true')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_OPEN_BRACKET')
             ->outIsIE('VARIABLE')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // spotting array with callable
        $this->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->savePropertyAs('code', 'name')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->isNot('lambda', 'true')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_VARIABLE')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
