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

class AccessPrivate extends Analyzer\Analyzer {
    public function analyze() {
        // methods
        // classname::method() direct class
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'static', 'self'))
             ->isNotLocalClass()
             ->classDefinition()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{it.get().value("code") == name}.in("NAME").out("PRIVATE").count().is(eq(1)) )')
             ->back('first');
        $this->prepareQuery();

        // classname::method() parent class through extension (not the direct class)
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'static', 'self'))
             ->isNotLocalClass()
             ->classDefinition()
             ->goToAllParents()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{it.get().value("code") == name}.in("NAME").out("PRIVATE").count().is(eq(1)) )')
             ->back('first');
        $this->prepareQuery();

        // Case of parent::
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->isNotLocalClass()
             ->goToClass()
             ->goToExtends()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{it.get().value("code") == name}.in("NAME").out("PRIVATE").count().is(eq(1)) )')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->isNotLocalClass()
             ->goToClass()
             ->goToExtends()
             ->goToAllParents()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{it.get().value("code") == name}.in("NAME").out("PRIVATE").count().is(eq(1)) )')
             ->back('first');
        $this->prepareQuery(); 

        // self / static::method() in parent class
        // static : the class which is called
        // self   : the class where the definition is
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->codeIs(array('self', 'static'))
             ->goToClass()
             // no local method
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{it.get().value("code") == name}.in("NAME").count().is(eq(0)) )')
             ->goToAllParents()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{it.get().value("code") == name}.in("NAME").out("PRIVATE").count().is(eq(1)) )')
             ->back('first')
             ;
        $this->prepareQuery(); 

        // properties
        // className::$property direct call
        $this->atomIs('Staticproperty')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->inIs('PROPERTY')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'static', 'self'))
             ->isNotLocalClass()

             ->goToClass()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Ppp").where( __.out("PRIVATE").count().is(eq(1)) ).out("PPP").coalesce(out("LEFT"),  __.filter{true} ).filter{it.get().value("code") == name}.count().is(eq(1)) )')

             ->back('first');
        $this->prepareQuery();
        
        // class::$property
        $this->atomIs('Staticproperty')
             ->analyzerIsNot('self')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->inIs('PROPERTY')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->isNotLocalClass()

             ->goToClass()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Ppp").where( __.out("PRIVATE").count().is(eq(1)) ).out("PPP").coalesce(out("LEFT"),  __.filter{true} ).filter{it.get().value("code") == name}.count().is(eq(1)) )')

             ->back('first');
        $this->prepareQuery();

        // parent::$property
        $this->atomIs('Staticproperty')
             ->analyzerIsNot('self')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->inIs('PROPERTY')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->isNotLocalClass()

             ->goToClass()
             ->goToExtends()
             ->goToAllParents()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Ppp").where( __.out("PRIVATE").count().is(eq(1)) ).out("PPP").coalesce(out("LEFT"),  __.filter{true} ).filter{it.get().value("code") == name}.count().is(eq(1)) )')

             ->back('first');
        $this->prepareQuery();

        return false;
    }
}

?>
