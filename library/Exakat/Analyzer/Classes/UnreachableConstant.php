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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UnreachableConstant extends Analyzer {
    public function analyze() {
        // class x { private const A = 1;} echo x::A;
        
        // Outside a class
        $this->atomIs('Staticconstant')
             ->hasNoClass()
             ->inIs('DEFINITION')
             ->inIs('CONST')
             ->is('visibility', array('protected', 'private'))
             ->back('first');
        $this->prepareQuery();

        // Outside a family class
        $this->atomIs('Staticconstant')
             ->goToClass()
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->inIs('DEFINITION')
             ->inIs('CONST')
             ->is('visibility', 'private')
             ->goToClass()
             ->notSamePropertyAs('fullnspath', 'fnp')
             ->back('first');
        $this->prepareQuery();
        
        
    }
}

?>
