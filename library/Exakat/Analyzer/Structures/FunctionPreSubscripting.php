<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class FunctionPreSubscripting extends Analyzer {
    protected $phpVersion = '5.4+';
    
    public function analyze() {
        // $x = f();
        // $x['e']
        // instead of f()['e']
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs(self::$FUNCTIONS_CALLS)
             ->back('first')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varray')
             ->back('first')
             ->nextSibling() // one must check more than the next sibling
             ->atomIs('Assignation')
             ->_as('second')
             ->outIs('RIGHT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'varray')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
