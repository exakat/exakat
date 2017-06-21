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

namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class DontEchoError extends Analyzer {
    public function analyze() {
        // echo mysql_error();
        $errorMessageFunctions = $this->loadIni('errorMessageFunctions.ini', 'functions');
        $errorMessageFunctions = $this->makeFullNsPath($errorMessageFunctions);
        
        $this->atomFunctionIs(array('\\echo', '\\print', '\\die', '\\exit'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Functioncall')
             ->raw('where( __.out("NAME").hasLabel("Array", "Variable", "Member", "Staticproperty", "Methodcall", "Staticmethodcall").count().is(eq(0)))')
             ->tokenIs(self::$FUNCTIONS_TOKENS)
             ->fullnspathIs($errorMessageFunctions)
             ->back('first');
        $this->prepareQuery();

        // echo 'error '.pg_error();
        $this->atomFunctionIs(array('\\echo', '\\print', '\\die', '\\exit'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->atomIs('Functioncall')
             ->raw('where( __.out("NAME").hasLabel("Array", "Variable", "Member", "Staticproperty", "Methodcall", "Staticmethodcall").count().is(eq(0)))')
             ->fullnspathIs($errorMessageFunctions)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
