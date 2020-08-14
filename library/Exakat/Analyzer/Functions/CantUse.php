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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class CantUse extends Analyzer {
    public function analyze(): void {
        // Function foo() { throw new exception(); }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomIs('Throw')
             ->back('first');
        $this->prepareQuery();

        // Function foo() { trigger_error(); }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->functioncallIs(array('\\trigger_error', '\\user_error'))
             ->back('first');
        $this->prepareQuery();

        // propagation to custom functions
        // Function foo() { trigger_error(); }
        // Function bar() { bar(); }
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomIs(self::FUNCTIONS_CALLS)
             ->inIs('DEFINITION')
             ->analyzerIs('self')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
