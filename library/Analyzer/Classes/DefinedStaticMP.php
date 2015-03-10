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

class DefinedStaticMP extends Analyzer\Analyzer {
    public function analyze() {
        // static::method() 1rst level
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::method() 2nd level
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::method() 3rd level
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::$property 1rst level
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::$property 2nd level
        $this->atomIs('Staticproperty')
             ->analyzerIsNot('self')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();

        // static::$property 3rd level
        $this->atomIs('Staticproperty')
             ->analyzerIsNot('self')
             ->outIs('CLASS')
             ->code(array('static', 'self'))
             ->back('first')
             ->outIs('PROPERTY')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Ppp").out("DEFINE").has("code", name).any()}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
