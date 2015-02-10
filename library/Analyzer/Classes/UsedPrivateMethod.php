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


namespace Analyzer\Classes;

use Analyzer;

class UsedPrivateMethod extends Analyzer\Analyzer {

    public function analyze() {
        // method used in a static methodcall \a\b::b()
        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'classname')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateMethod')
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'classname')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('method');
        $this->prepareQuery();

        // method used in a static methodcall static::b() or self
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateMethod')
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('method');
        $this->prepareQuery();

        // method used in a normal methodcall with $this $this->b()
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIsNot('Analyzer\\Classes\\UsedPrivateMethod')
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->code('$this')
             ->inIs('OBJECT')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('method');
        $this->prepareQuery();

        // method used in a new (constructor)
        $this->atomIs("Class")
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasOut('PRIVATE')
             ->_as('method')
             ->outIs('NAME')
             ->code('__construct')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomInside('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('method');
        $this->prepareQuery();

        // __destruct is considered automatically checked
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->code('__destruct')
             ->inIs('NAME');
        $this->prepareQuery();
    }
}

?>
