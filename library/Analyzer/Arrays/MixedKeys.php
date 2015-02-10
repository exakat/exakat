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


namespace Analyzer\Arrays;

use Analyzer;

class MixedKeys extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasIn('VALUE')
             ->tokenIs('T_ARRAY')
             ->fullnspath('\\array')
             ->outIs('ARGUMENTS')
             ->raw('filter{ m=[:]; 
                            it.out("ARGUMENT").groupBy(m){
              if (it.out("KEY").any() && it.out("KEY").next().atom in ["Identifier", "Staticconstant"]) { "a" } else { "b" }
             }{it}{it.size()}.iterate();
m.size() > 1; }');
        $this->prepareQuery();
    }
}

?>
