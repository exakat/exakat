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

class Recursive extends Analyzer {
    public function analyze() {
        // function foo() { foo(); }
        $this->atomIs('Function')
             ->savePropertyAs('fullcode', 'fqn')
             ->outIs('DEFINITION')
             ->atomIs('Functioncall')
             ->goToInstruction('Function')
             ->samePropertyAs('fullcode', 'fqn');
        $this->prepareQuery();

        // $a = function () use (&$a) { foo(); }
        $this->atomIs('Closure')
             ->outIs('USE')
             ->is('reference', true)
             ->savePropertyAs('code', 'useVar')
             ->back('first')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'useVar', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // function foo() { $this->foo(); }
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->savePropertyAs('fullcode', 'fqn')
             ->outIs('DEFINITION')
             ->atomIs(array('Methodcall', 'Staticmethodcall'))
             ->goToInstruction(self::$FUNCTIONS_METHOD)
             ->samePropertyAs('fullcode', 'fqn');
        $this->prepareQuery();
    }
}

?>
