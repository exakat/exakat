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

class UsedPrivateProperty extends Analyzer\Analyzer {

    public function analyze() {
        // property used in a staticmethodcall \a\b::$b
        $this->atomIs('Ppp')
             ->hasOut('PRIVATE')

             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->_as('ppp')
             ->savePropertyAs('code', 'property')
             ->goToClassTrait()
             ->raw('where( __.out("NAME").hasLabel("Void").count().is(eq(0)) )')
             ->savePropertyAs('fullnspath', 'classe')
             ->raw('where( g.V().hasLabel("Staticproperty").out("CLASS").hasLabel("T_STRING", "T_NS_SEPARATOR").filter{ it.get().value("fullnspath") == classe }.in("CLASS")
                                                           .out("PROPERTY").filter{ it.get().value("code") == property }.in("PROPERTY")
                                                           .count().is(neq(0)) )')
             ->back('first')
             ->outIs('PPP')
             ->outIsIE('LEFT');
        $this->prepareQuery();

        // property used in a static property static::$b or self::$b
        $this->atomIs('Class', 'Trait')
             ->hasName()
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->hasOut('PRIVATE')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->analyzerIsNot('self')
             ->_as('ppp')
             ->savePropertyAs('code', 'x')
             ->inIsIE('LEFT')
             ->inIs('PPP')
             ->inIs('ELEMENT')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->samePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'x')
             ->back('ppp');
        $this->prepareQuery();

        // property used in a static property static::$b[] or self::$b[]
        $this->atomIs('Class', 'Trait')
             ->hasName()
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->hasOut('PRIVATE')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->_as('ppp')
             ->savePropertyAs('code', 'x')
             ->inIsIE('LEFT')
             ->inIs('PPP')
             ->inIs('ELEMENT')
             ->atomInside('Staticproperty')
             ->outIs('CLASS')
             ->fullnspathIs('fnp')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'x')
             ->back('ppp');
        $this->prepareQuery();

        // property used in a normal methodcall with $this $this->b()
        $this->atomIs('Class', 'Trait')
             ->hasName()
             ->savePropertyAs('fullnspath', 'classname')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Ppp')
             ->analyzerIsNot('self')
             ->hasOut('PRIVATE')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->savePropertyAs('propertyname', 'x')
             ->_as('ppp')
             ->inIsIE('LEFT')
             ->inIs('PPP')
             ->inIs('ELEMENT')
             ->atomInside('Property')
             ->outIs('OBJECT')
             ->codeIs('$this')
             ->inIs('OBJECT')
             ->outIs('PROPERTY')
             ->samePropertyAs('code', 'x')
             ->back('ppp');
        $this->prepareQuery();
    }
}
?>
