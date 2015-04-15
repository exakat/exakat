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

class MustReturn extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\MethodDefinition');
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->hasNoOut('ABSTRACT')
             ->raw('filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Interface").any() == false}')
             ->outIs('NAME')
             ->code(array('__call', '__callStatic', '__get', '__isset', '__sleep', '__toString', '__set_state',
                          '__invoke', '__debugInfo'))
             ->analyzerIs('Analyzer\\Classes\\MethodDefinition')
             ->back('first')
             ->noAtomInside('Return');
        $this->prepareQuery();
    }
}

?>
