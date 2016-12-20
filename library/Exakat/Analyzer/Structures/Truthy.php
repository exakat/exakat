<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Truthy extends Analyzer {
    public function dependsOn() {
        return array('Structures/Falsy');
    }
    
    public function analyze() {
        // Just not a falsy
        $this->atomIs(array('Integer', 'Real', 'Boolean'))
             ->analyzerIsNot('Structures/Falsy');
        $this->prepareQuery();

        // String
        $this->atomIs('String')
             ->analyzerIsNot('Structures/Falsy')
             ->hasNoIn('CONCAT');
        $this->prepareQuery();

        // Note : heredoc always includes a final \n
        $this->atomIs('Heredoc')
             ->outIs('CONCAT')
             ->atomIs('String')
             ->codeIsNot(array('', "\n"))
             ->back('first');
        $this->prepareQuery();

        // array
        $this->atomFunctionIs('\array')
             ->analyzerIsNot('Structures/Falsy');
        $this->prepareQuery();
        
        // NULL
        // No

        // object
        // How to test?

        // resource
        // resources are never literals
    }
}

?>
