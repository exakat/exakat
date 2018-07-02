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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class Recursive extends Analyzer {
    public function analyze() {

        // function foo() { foo(); }
        $this->atomIs('Function')
             ->savePropertyAs('fullcode', 'name')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition(self::$FUNCTIONS_CALLS)
             ->functionDefinition()
             ->samePropertyAs('fullcode', 'name')
             ->back('first');
        $this->prepareQuery();

        // function foo() { $this->foo(); }
        $this->atomIs('Method')
             ->savePropertyAs('lccode', 'name')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Methodcall')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->outIs('METHOD')
             ->samePropertyAs('lccode', 'name')
             ->back('first');
        $this->prepareQuery();

        // function foo() { self::foo(); }
        $this->atomIs('Method')
             ->savePropertyAs('lccode', 'name')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Staticmethodcall')
             ->outIs('METHOD')
             ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->inIs('DEFINITION')
             ->outIs('METHOD')
             ->samePropertyAs('lccode', 'name');
        $this->prepareQuery();
    }
}

?>
