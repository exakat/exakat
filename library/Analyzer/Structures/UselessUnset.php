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


namespace Analyzer\Structures;

use Analyzer;

class UselessUnset extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\Arguments');
    }
    
    public function analyze() {
        // unset on arguments, reference or value
        $this->atomIs('Functioncall')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
             ->back('first');
        $this->prepareQuery();

        // unset on global 
        $this->atomIs('Functioncall')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->outIs('ARGUMENTS')
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
        $this->atomIs('Functioncall')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInside('Static')
             ->inIs('STATIC')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'varname')
             ->back('first');
        $this->prepareQuery();

        // unset on foreach  (variable)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->_as('result')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
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
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->_as('result')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('code', 'varname')
             ->back('result');
        $this->prepareQuery();



    // unset as operator
        // unset on arguments, reference or value
        $this->atomIs('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->outIs('CAST')
             ->atomIs('Variable')
             ->analyzerIs('Analyzer\\Variables\\Arguments')
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
             ->inIs('STATIC')
             ->outIs('DEFINE')
             ->samePropertyAs('code', 'varname')
             ->back('first');
        $this->prepareQuery();

        // unset on foreach  (variable)
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
