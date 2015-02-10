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


namespace Analyzer\Structures;

use Analyzer;

class ConditionalStructures extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\MethodDefinition');
    }
    
    public function analyze() {
        // classes, interfaces, Traits
        $this->atomIs(array("Class", 'Interface', 'Trait'))
             ->analyzerIsNot('Analyzer\\Classes\\MethodDefinition')
             ->atomAboveIs('Ifthen')
             ->back('first');
        $this->prepareQuery();

        // functions 
        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIsNot('Analyzer\\Classes\\MethodDefinition')
             ->back('first')
             ->atomAboveIs('Ifthen')
             ->back('first');
        $this->prepareQuery();

       // constants
        $this->atomIs('Functioncall')
             ->fullnspath('\\define')
             ->atomAboveIs('Ifthen')
             ->back('first')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0);
        $this->prepareQuery();
 
    }
}

?>
