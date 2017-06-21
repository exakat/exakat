<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class NoNeedForElse extends Analyzer {
    public function analyze() {
        $breaks = array('Return', 'Break', 'Continue', 'Break');
        // if () { return; } else  { no-return; }
        $this->atomIs('Ifthen')
             ->hasOut('ELSE')
             ->outIs('THEN')
             ->outIs('EXPRESSION')
             ->atomIs($breaks)
             ->back('first')
             ->outIs('ELSE')
             ->raw('where( __.out("EXPRESSION").hasLabel("Return", "Break", "Continue").count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();

        // if () { no-return; } else  { return; }
        $this->atomIs('Ifthen')
             ->hasOut('ELSE')
             ->outIs('ELSE')
             ->outIs('EXPRESSION')
             ->atomIs($breaks)
             ->back('first')
             ->outIs('THEN')
             ->raw('where( __.out("EXPRESSION").hasLabel("Return", "Break", "Continue").count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
