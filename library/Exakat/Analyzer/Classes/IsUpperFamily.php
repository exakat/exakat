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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class IsUpperFamily extends Analyzer {
    public function analyze() {
        // Staticmethodcall
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'method')
             
             ->goToClass()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{it.get().value("code").toLowerCase() == method.toLowerCase() }.count().is(eq(0)) )')

             ->goToAllParents()
             ->atomIsNot('Interface')
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{it.get().value("code").toLowerCase() == method.toLowerCase() }.count().is(neq(0)) )')

             ->back('first')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // Staticproperty
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('PROPERTY')
             ->tokenIs('T_VARIABLE')
             ->savePropertyAs('code', 'property')
             
             ->goToClass()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Ppp").out("PPP").coalesce(__.out("LEFT"), __.filter{true; }).filter{it.get().value("code") == property }.count().is(eq(0)) )')

             ->goToAllParents()
             ->atomIsNot('Interface')
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Ppp").out("PPP").coalesce(__.out("LEFT"), __.filter{true; }).filter{it.get().value("code") == property }.count().is(neq(0)) )')

             ->back('first')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // Staticconstant
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('CONSTANT')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'constant')
             
             ->goToClass()
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Const").out("CONST").out("NAME").filter{it.get().value("code") == constant }.count().is(eq(0)) )')

             ->goToAllParents()
             ->atomIsNot('Interface')
             ->raw('where( __.out("BLOCK").out("ELEMENT").hasLabel("Const").out("CONST").out("NAME").filter{it.get().value("code") == constant }.count().is(neq(0)) )')

             ->back('first')
             ->analyzerIsNot('self');
        $this->prepareQuery();
    }
}

?>
