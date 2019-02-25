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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class DemeterLaw extends Analyzer {
    public function analyze() {
        // law of demeter
        // Still missing foreach and fluent interfaces
        $this->atomIs('Methodcall')
            // Not a local method
             ->outIsIE('OBJECT')
             ->outIsIE('VARIABLE')
             ->atomIsNot(array('This', 'Phpvariable'))
             
            // variable is not an argument
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->inIs('DEFINITION')
                             ->inIs('NAME')
                             ->inIs('ARGUMENT')
                             )
                )

            // variable is not a global
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->inIs('DEFINITION')
                             ->inIs('GLOBAL')
                             )
                )

            // variable is not created locally
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->inIs('DEFINITION')
                             ->outIs('DEFINITION')
                             ->inIs('LEFT')
                             ->outIs('RIGHT')
                             ->atomIs('New')
                             )
                )

            // variable is not a caught exception
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->inIs('DEFINITION')
                             ->outIs('DEFINITION')
                             ->inIs('VARIABLE')
                             ->atomIs('Catch')
                             )
                )

             ->back('first');
        $this->prepareQuery();
    }
}

?>
