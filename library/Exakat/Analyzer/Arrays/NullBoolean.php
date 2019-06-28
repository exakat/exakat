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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class NullBoolean extends Analyzer {
    public function analyze() {
        // true[1], null[0]
        $this->atomIs(array('Null', 'Boolean'))
             ->inIs('VARIABLE');
        $this->prepareQuery();
        
        // const A = true; echo A[1];
        $this->atomIs(array('Identifier', 'Nsname', 'Staticconstant'))
             ->inIs('DEFINITION')
             ->outIs('VALUE')
             ->atomIs(array('Null', 'Boolean'))
             ->back('first')
             ->inIs('VARIABLE');
        $this->prepareQuery();
        
    }
}

?>
