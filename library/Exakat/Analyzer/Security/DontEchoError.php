<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
    public function analyze(): void {
        // echo mysql_error();
        $errorMessageFunctions = $this->loadIni('errorMessageFunctions.ini', 'functions');
        $errorMessageFunctions = makeFullNsPath($errorMessageFunctions);

        $displayFunctions = $this->loadIni('displayFunctions.ini', 'functions');
        $displayFunctions = makeFullNsPath($displayFunctions);

        $this->atomFunctionIs($displayFunctions)
             ->outIs('ARGUMENT')
             ->atomIs('Functioncall')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('NAME')
                             ->atomIs(array('Array', 'Variable', 'Member', 'Staticproperty', 'Methodcall', 'Staticmethodcall'))
                     )
             )
             ->has('fullnspath')
             ->fullnspathIs($errorMessageFunctions)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs(array('Echo', 'Print', 'Exit'))
             ->outIs('ARGUMENT')
             ->outIsIE('CODE')
             ->atomIs('Functioncall')
             ->has('fullnspath')
             ->fullnspathIs($errorMessageFunctions)
             ->back('first');
        $this->prepareQuery();

        // echo 'error '.pg_error();
        $this->atomFunctionIs($displayFunctions)
             ->outIs('ARGUMENT')
             ->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->atomIs('Functioncall')
             ->fullnspathIs($errorMessageFunctions)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs(array('Echo', 'Print', 'Exit'))
             ->outIs('ARGUMENT')
             ->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->atomIs('Functioncall')
             ->fullnspathIs($errorMessageFunctions)
             ->back('first');
        $this->prepareQuery();

        // try {} catch ($e) { echo $e->getMessage(); }
        $this->atomIs('Try')
             ->outIs('CATCH')
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'exception')
             ->inIs('VARIABLE')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Methodcall')
             ->outIs('OBJECT')
             ->samePropertyAs('code', 'exception')
             ->inIs('OBJECT')
             ->outIs('METHOD')
             ->codeIs(array('getMessage', 'getTraceAsString'), self::TRANSLATE, self::CASE_INSENSITIVE)
             ->inIs('METHOD')
             ->inIs('ARGUMENT')
             ->atomIs(array('Echo', 'Print', 'Exit', 'Functioncall'))
             ->has('fullnspath')
             ->fullnspathIs($displayFunctions);
        $this->prepareQuery();

        // try {} catch ($e) { echo $e.PHP_EOL; }
        $this->atomIs('Try')
             ->outIs('CATCH')
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'exception')
             ->inIs('VARIABLE')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition(array('Echo', 'Print', 'Exit', 'Functioncall'))
             ->has('fullnspath')
             ->as('results')
             ->atomIs(array('Echo', 'Print', 'Exit', 'Functioncall'))
             ->has('fullnspath')
             ->fullnspathIs($displayFunctions)
             ->outIs('ARGUMENT')
             ->outIsIE('CONCAT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'exception')
             ->back('results');
        $this->prepareQuery();

        // ini_set('display_error', 1)
        $this->atomFunctionIs('\\ini_set')
             ->outWithRank('ARGUMENT', 0)
             ->has('noDelimiter')
             ->noDelimiterIs('display_errors')
             ->back('first')
             ->outWithRank('ARGUMENT', 1)
             ->has('boolean')
             ->is('boolean', true)
             ->back('first');
        $this->prepareQuery();

    }
}

?>
