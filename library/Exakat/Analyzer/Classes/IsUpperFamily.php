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

class IsUpperFamily extends Analyzer {
    public function analyze() {
        // Staticmethodcall
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->atomIs(array('Identifier', 'Nsname', 'Static', 'Self', 'Parent'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->tokenIs('T_STRING') // Avoid dynamical names
             ->savePropertyAs('fullnspath', 'methode')
             
             ->goToClass()
             ->raw('not( where( __.out("METHOD").hasLabel("Method").filter{ (it.get().value("fullnspath") =~ "::" + methode.replaceAll("\\\\\\\\", "\\\\\\\\\\\\\\\\") ).getCount() != 0 } ) )')

             ->goToAllParents()
             ->atomIsNot('Interface')
             ->raw('where( __.out("METHOD").hasLabel("Method").filter{ (it.get().value("fullnspath") =~ "::" + methode.replaceAll("\\\\\\\\", "\\\\\\\\\\\\\\\\") ).getCount() != 0 } )')

             ->back('first');
        $this->prepareQuery();

        // Staticproperty
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->atomIs(array('Identifier', 'Nsname', 'Static', 'Self', 'Parent'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('MEMBER')
             ->tokenIs('T_VARIABLE')
             ->savePropertyAs('code', 'property')
             
             ->goToClass()
             ->raw('not( where( __.out("PPP").hasLabel("Ppp").out("PPP").coalesce(__.out("NAME"), __.filter{true; }).filter{it.get().value("code") == property } ) )')

             ->goToAllParents()
             ->atomIsNot('Interface')
             ->raw('where( __.out("PPP").hasLabel("Ppp").out("PPP").coalesce(__.out("NAME"), __.filter{true; }).filter{it.get().value("code") == property }.count().is(neq(0)) )')

             ->back('first');
        $this->prepareQuery();

        // Staticconstant
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->atomIs(array('Identifier', 'Nsname', 'Static', 'Self', 'Parent'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('CONSTANT')
             ->tokenIs('T_STRING')
             ->savePropertyAs('code', 'constant')
             
             ->goToClass()
             ->raw('not( where( __.out("CONST").hasLabel("Const").out("CONST").out("NAME").filter{it.get().value("code") == constant } ) )')

             ->goToAllParents()
             ->atomIsNot('Interface')
             ->raw('where( __.out("CONST").hasLabel("Const").out("CONST").out("NAME").filter{it.get().value("code") == constant }.count().is(neq(0)) )')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
