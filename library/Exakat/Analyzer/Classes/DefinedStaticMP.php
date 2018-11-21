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

class DefinedStaticMP extends Analyzer {
    public function analyze() {
        // static::method() 1rst level
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->atomIs(array('Static', 'Self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('lccode', 'name')
             ->goToClass()
             ->raw('where( __.out("METHOD").out("NAME").filter{ it.get().value("lccode") == name} )')
             ->back('first');
        $this->prepareQuery();

        // static::method() parents and beyond
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->atomIs(array('Static', 'Self'))
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('lccode', 'name')
             ->goToClass()
             ->goToAllParents()
             ->raw('where( __.out("METHOD").out("NAME").filter{ it.get().value("lccode") == name} )')
             ->back('first');
        $this->prepareQuery();

        // static::$property the current class
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->atomIs(array('Static', 'Self'))
             ->back('first')
             ->outIs('MEMBER')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->outIs('PPP')
             ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // static::$property Parents
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->atomIs(array('Static', 'Self'))
             ->back('first')
             ->outIs('MEMBER')
             ->outIsIE('VARIABLE')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->goToAllParents()
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->outIs('PPP')
             ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
