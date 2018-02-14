<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class DefinedStaticMP extends Analyzer {
    public function analyze() {
        // static::method() 1rst level
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->codeIs(array('static', 'self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->raw('where( __.out("METHOD").out("NAME").filter{ it.get().value("code") == name}.count().is(eq(1)) )')
             ->back('first');
        $this->prepareQuery();

        // static::method() parents and beyond
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->codeIs(array('static', 'self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->goToAllParents()
             ->raw('where( __.out("METHOD").out("NAME").filter{ it.get().value("code") == name}.count().is(eq(1)) )')
             ->back('first');
        $this->prepareQuery();

        // static::$property the current class
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->codeIs(array('static', 'self'))
             ->back('first')
             ->outIs('MEMBER')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // static::$property Parents
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->codeIs(array('static', 'self'))
             ->back('first')
             ->outIs('MEMBER')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->goToAllParents()
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
