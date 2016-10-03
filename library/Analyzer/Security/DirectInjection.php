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


namespace Analyzer\Security;

use Analyzer;

class DirectInjection extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Security/SensitiveArgument');
    }
    
    public function analyze() {
        $vars = $this->loadIni('php_incoming.ini');
        $vars = $vars['incoming'];
        
        $safe = array('DOCUMENT_ROOT', 'REQUEST_TIME', 'SERVER_PORT', 'SERVER_NAME', 'REQUEST_TIME_FLOAT',
                      'SCRIPT_NAME', 'SERVER_ADMIN', '_');
        $safeIndex = 'or( __.out("VARIABLE").has("code", "\\$_SERVER").count().is(eq(0)), 
                          __.out("INDEX").hasLabel("String")
                            .where(__.out("ELEMENT").count().is(eq(0)) )
                            .has("noDelimiter", within(["' . implode('", "', $safe) . '"]))
                            .count().is(eq(0)))';

        // Relayed call to another function
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->inIsIE('VARIABLE')
             ->raw($safeIndex)
             ->_as('result')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->functionDefinition()

             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')

             ->savePropertyAs('code', 'varname')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')

             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->analyzerIs('Security/SensitiveArgument')
             ->outIsIE('CODE')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'varname')
             ->back('result');
        $this->prepareQuery();

        // $_GET/_POST ... directly as argument of PHP functions
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->analyzerIs('Security/SensitiveArgument')
             ->inIsIE('CODE')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        // $_GET/_POST ['index'] (one level).. directly as argument of PHP functions
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->inIs('VARIABLE')
             ->raw($safeIndex)
             ->inIsIE('CODE')
             ->analyzerIs('Security/SensitiveArgument')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        // $_GET/_POST ['index']['index2'] (2 levels and more)... directly as argument of PHP functions
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->goToArray()
             ->analyzerIs('Security/SensitiveArgument')
             ->raw($safeIndex)
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation!
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->goToArray()
             ->raw($safeIndex)
             ->inIs('CONCAT')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // $_GET/_POST array... inside a string is useless and safe (will print Array)
        // "$_GET/_POST ['index']"... inside a string or a concatenation is unsafe
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->goToArray()
             ->raw($safeIndex)
             ->inIs('CONCAT')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // foreach (looping on incoming variables)
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->goToArray()
             ->inIs('SOURCE');
        $this->prepareQuery();

    }
}

?>
