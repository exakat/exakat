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
        $hasPrivateMethodDefinition = 'where( __.out("BLOCK").out("ELEMENT").hasLabel("Function")
                                                                            .out("NAME").filter{it.get().value("code") == name}.in("NAME")
                                                                            .out("PRIVATE").count().is(eq(1)) )';
        $notHasPrivateMethodDefinition = 'where( __.out("BLOCK").out("ELEMENT").hasLabel("Function")
                                                                               .out("NAME").filter{it.get().value("code") == name}.in("NAME")
                                                                               .out("PRIVATE").count().is(eq(0)) )';

        $hasPrivateProperty = 'where( __.out("BLOCK").out("ELEMENT").hasLabel("Ppp").where( __.out("PRIVATE").count().is(eq(1)) ).out("PPP").coalesce(out("LEFT"),  __.filter{true} ).filter{it.get().value("code") == name}.count().is(eq(1)) )';
        $hasNotPrivateProperty = 'where( __.out("BLOCK").out("ELEMENT").hasLabel("Ppp").where( __.out("PRIVATE").count().is(eq(1)) ).out("PPP").coalesce(out("LEFT"),  __.filter{true} ).filter{it.get().value("code") == name}.count().is(eq(0)) )';
        
        // methods
        // classname::method() direct class
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_STATIC'))
             ->codeIsNot(array('parent', 'static', 'self'))
             ->isNotLocalClass()
             ->classDefinition()
             ->raw($hasPrivateMethodDefinition)
             ->back('first');
        $this->prepareQuery();

        // classname::method() parent class through extension (not the direct class)
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->inIs('METHOD')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_STATIC'))
             ->codeIsNot(array('parent', 'static', 'self'))
             ->isNotLocalClass()
             ->classDefinition()
             ->goToAllParents()
             ->raw($hasPrivateMethodDefinition)
             ->back('first')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // Case of parent::
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->tokenIs('T_STRING')
             ->codeIs('parent')
             ->isNotLocalClass()
             ->goToClass()
             ->goToExtends()
             ->raw($hasPrivateMethodDefinition)
             ->back('first')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // In the grand-parents
        $this->atomIs('Staticmethodcall')
             ->analyzerIsNot('self')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_STATIC'))
             ->codeIs('parent')
             ->isNotLocalClass()
             ->goToClass()
             // Go to All parent is in the next raw
             ->raw('where( __.repeat( out("EXTENDS").in("DEFINITION") ).emit().times(6).'.$hasPrivateMethodDefinition.'.count().is(neq(0)) )')
             ->back('first')
             ->analyzerIsNot('self');
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
             ->tokenIs(array('T_STRING', 'T_STATIC'))
             ->codeIs(array('self', 'static'))
             ->goToClass()
             // no local method
             ->raw($notHasPrivateMethodDefinition)
             ->goToAllParents()
             ->raw($hasPrivateMethodDefinition)
             ->back('first')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // properties
        // className::$property direct call
        $this->atomIs('Staticproperty')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->inIs('PROPERTY')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_STATIC'))
             ->codeIsNot(array('parent', 'static', 'self'))
             ->isNotLocalClass()

             ->goToClass()
             ->raw($hasPrivateProperty)

             ->back('first');
        $this->prepareQuery();
        
        // class::$property
        $this->atomIs('Staticproperty')
             ->analyzerIsNot('self')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->inIs('PROPERTY')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_STATIC'))
             ->codeIs('parent')
             ->isNotLocalClass()

             ->goToClass()
             ->raw($hasPrivateProperty)

             ->back('first');
        $this->prepareQuery();

        // parent::$property
        $this->atomIs('Staticproperty')
             ->analyzerIsNot('self')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->inIs('PROPERTY')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_STATIC'))
             ->codeIs('parent')
             ->isNotLocalClass()

             ->goToClass()
             ->goToExtends()
             ->goToAllParents()
             ->raw($hasPrivateProperty)

             ->back('first');
        $this->prepareQuery();

        return false;
    }
}

?>
