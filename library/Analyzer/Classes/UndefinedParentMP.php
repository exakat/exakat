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

class UndefinedParentMP extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Composer/IsComposerNsname');
    }
    
    public function analyze() {
        // parent::method()
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->hasNoOut('EXTENDS')
             ->back('first');
        $this->prepareQuery();

        // parent::method()
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->hasClassDefinition()
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->goToAllParents()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").where( __.out("PRIVATE").count().is(eq(0)) ).out("NAME").filter{ it.get().value("code") == name}.count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();

        // parent::$property without parent
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->goToClass()
             ->hasNoOut('EXTENDS')
             ->back('first');
        $this->prepareQuery();

        // parent::$property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->codeIs('parent')
             ->hasClassDefinition()
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->goToAllParents()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Ppp").where( __.out("PRIVATE").count().is(eq(0)) ).out("PPP").coalesce(out("LEFT"),  __.filter{true} ).filter{ it.get().value("propertyname") == name}.count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
