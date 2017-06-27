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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class NoReturnUsed extends Analyzer {
    public function analyze() {
        // Functions
        $this->atomIs('Function')
             ->outIs('BLOCK')
             ->atomInside('Return')
             ->outIs('RETURN')
             ->atomIsNot('Void')
             ->back('first')
             ->raw('where( __.out("DEFINITION") )')
             ->raw('not(where( __.out("DEFINITION").not(where( __.in("EXPRESSION")))) )');
        $this->prepareQuery();

        // Methods
        $this->atomIs('Method')
             ->hasOut('STATIC')
             ->savePropertyAs('code', 'method')
             ->outIs('BLOCK')
             ->atomInside('Return')
             ->outIs('RETURN')
             ->atomIsNot('Void')
             ->back('first')
             ->goToClass()
             ->raw('where( __.out("DEFINITION").in("CLASS").hasLabel("Staticmethodcall").out("METHOD").has("token", "T_STRING").filter{ it.get().value("code").toLowerCase() == method.toLowerCase(); } )')
             ->raw('not(where( __.out("DEFINITION").in("CLASS").hasLabel("Staticmethodcall").out("METHOD").has("token", "T_STRING").filter{ it.get().value("code").toLowerCase() == method.toLowerCase(); }.in("METHOD").not(where( __.in("EXPRESSION"))) ) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
