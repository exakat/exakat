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

class NonStaticMethodsCalledStatic extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\MethodDefinition",
                     "Analyzer\\Classes\\StaticMethods"
        );
    }

    public function analyze() {
        // check outside the class : the first found class has the method, and it is not static
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->codeIsNot('__construct')
             ->savePropertyAs('code', 'methodname')
             ->inIs('METHOD')

             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'self', 'static'))
             ->classDefinition()

             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasNoOut('STATIC')
             ->outIs('NAME')
             ->samePropertyAs('code', 'methodname')

             ->back('first');
        $this->prepareQuery();

        // check outside the class : the first found class has not method
        // Here, we find methods that are in the grand parents, and not static. 
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->codeIsNot('__construct')
             ->savePropertyAs('code', 'methodname')
             ->inIs('METHOD')

             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'self', 'static'))
             ->classDefinition()
             ->goToAllParents()

             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasNoOut('STATIC')
             ->outIs('NAME')
             ->samePropertyAs('code', 'methodname')

             ->back('first');
        $this->prepareQuery();
        
        // static call with self or static::
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->codeIsNot('__construct')
             ->savePropertyAs('code', 'methodname')
             ->inIs('METHOD')

             ->outIs('CLASS')
             ->code(array('self', 'static'))
             ->goToClass()

             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasNoOut('STATIC')
             ->outIs('NAME')
             ->samePropertyAs('code', 'methodname')

             ->back('first');
        $this->prepareQuery();

        // static call with parent::
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->codeIsNot('__construct')
             ->savePropertyAs('code', 'methodname')
             ->inIs('METHOD')

             ->outIs('CLASS')
             ->code('parent')
             ->goToClass()
             ->goToAllParents()

             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasNoOut('STATIC')
             ->outIs('NAME')
             ->samePropertyAs('code', 'methodname')
             ->back('first')
             ;
        $this->prepareQuery();
    }
}

?>
