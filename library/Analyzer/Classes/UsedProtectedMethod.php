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


namespace Analyzer\Classes;

use Analyzer;

class UsedProtectedMethod extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Classes/IsNotFamily');
    }

    public function analyze() {
        // method used in a static methodcall \a\b::b()
        $this->atomIs('Class')
             ->hasName()
             ->savePropertyAs('fullnspath', 'classname')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('method')
             ->hasOut('PROTECTED')
             ->outIs('NAME')
             ->codeIsNot(array('__construct', '__destruct'))
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->goToClass()
             ->goToAllChildren(true)
             ->outIs('BLOCK')
             ->atomInside('Staticmethodcall')
             ->analyzerIsNot('Classes/IsNotFamily')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->samePropertyAs('code', 'name', true)
             ->back('method')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // method used in a normal methodcall with $this $this->b()
        $this->atomIs('Class')
             ->hasName()
             ->savePropertyAs('fullnspath', 'classname')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('method')
             ->hasOut('PROTECTED')
             ->outIs('NAME')
             ->codeIsNot(array('__construct', '__destruct'))
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->goToClass()
             ->goToAllChildren(true)
             ->outIs('BLOCK')
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->codeIs('$this')
             ->inIs('OBJECT')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->samePropertyAs('code', 'name', true)
             ->back('method')
             ->analyzerIsNot('self');
        $this->prepareQuery();
    }
}

?>
