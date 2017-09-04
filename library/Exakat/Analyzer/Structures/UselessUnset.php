<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UselessUnset extends Analyzer {
    public function dependsOn() {
        return array('Variables/Arguments');
    }
    
    public function analyze() {
        // unset on arguments, reference or value
        $this->atomFunctionIs('\\unset')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->analyzerIs('Variables/Arguments')
             ->back('first');
        $this->prepareQuery();

        // unset on global
        $this->atomFunctionIs('\\unset')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInside('Global')
             ->outIs('GLOBAL')
             ->samePropertyAs('code', 'varname')
             ->back('first');
        $this->prepareQuery();

        // unset on static
        $this->atomFunctionIs('\\unset')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInside('Static')
             ->outIs('STATIC')
             ->samePropertyAs('code', 'varname')
             ->back('first');
        $this->prepareQuery();

        // unset on foreach  (variable or property)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('OBJECT')
             ->savePropertyAs('code', 'varname')
             ->inIsIE('OBJECT')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->functioncallIs('\\unset')
             ->_as('result')
             ->outIs('ARGUMENT')
             ->outIsIE('OBJECT')
             ->samePropertyAs('code', 'varname')
             ->inIsIE('OBJECT')
             ->raw('not( where( out("OBJECT").hasLabel("Member") ) )')
             ->back('result');
        $this->prepareQuery();

        // unset on foreach (KeyVal)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs('VALUE')
             ->outIsIE('OBJECT')        // Case it is a property...
             ->savePropertyAs('code', 'varname')
             ->inIsIE('OBJECT')
             ->inIs('VALUE')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->functioncallIs('\\unset')
             ->_as('result')
             ->outIs('ARGUMENT')
             ->outIsIE('OBJECT')
             ->samePropertyAs('code', 'varname')
             ->inIsIE('OBJECT')
             ->raw('not( where( out("OBJECT").hasLabel("Member") ) )')
             ->back('result');
        $this->prepareQuery();

        // unset on foreach (KeyVal)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('VALUE')
             ->atomIs('Member')
             ->savePropertyAs('fullcode', 'varname')
             ->inIsIE('VALUE')
             ->outIs('BLOCK')
             ->atomFunctionIs('\\unset')
             ->_as('result')
             ->outIs('ARGUMENT')
             ->samePropertyAs('fullcode', 'varname')
             ->back('result');
        $this->prepareQuery();

    // unset as operator
        // unset on arguments, reference or value
        $this->atomIs('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->outIs('CAST')
             ->atomIs('Variable')
             ->analyzerIs('Variables/Arguments')
             ->back('first');
        $this->prepareQuery();

        // unset on global
        $this->atomIs('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->outIs('CAST')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInside('Global')
             ->outIs('GLOBAL')
             ->samePropertyAs('code', 'varname')
             ->back('first');
        $this->prepareQuery();

        // unset on static
        $this->atomIs('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->outIs('CAST')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInside('Static')
             ->outIs('STATIC')
             ->samePropertyAs('code', 'varname')
             ->back('first');
        $this->prepareQuery();

        // unset on foreach (variable)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->_as('result')
             ->outIs('CAST')
             ->samePropertyAs('code', 'varname')
             ->back('result');
        $this->prepareQuery();

        // unset on foreach (KeyVal)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs('VALUE')
             ->savePropertyAs('code', 'varname')
             ->inIs('VALUE')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->_as('result')
             ->outIs('CAST')
             ->samePropertyAs('code', 'varname')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
