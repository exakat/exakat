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

class DirectInjection extends Analyzer {
    public function dependsOn() {
        return array('Security/SensitiveArgument');
    }
    
    public function analyze() {
        $vars = $this->loadIni('php_incoming.ini');
        $vars = $vars['incoming'];
        
        $safe = array('DOCUMENT_ROOT', 'REQUEST_TIME', 'REQUEST_TIME_FLOAT',
                      'SCRIPT_NAME', 'SERVER_ADMIN', '_');
        $safeIndex = 'or( __.out("VARIABLE").has("code", "\\$_SERVER").count().is(eq(0)), 
                          __.out("INDEX").hasLabel("String")
                            .where(__.out("CONCAT").count().is(eq(0)) )
                            .not(has("noDelimiter", within([' . makeList($safe) . '])))
                            .count().is(neq(0)))';

        // Relayed call to another function
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'rank')
             ->_as('result')
             ->raw($safeIndex)
             ->outIsIE('VARIABLE')
             ->atomIs(self::$VARIABLES_ALL)
             ->codeIs($vars, true)
             ->back('first')

             ->functionDefinition()
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')

             ->savePropertyAs('code', 'varname')
             ->inIs('ARGUMENT')

             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->outIs('ARGUMENT')
             ->analyzerIs('Security/SensitiveArgument')
             ->outIsIE('CODE')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'varname')
             ->back('result');
        $this->prepareQuery();

        // $_GET/_POST ... directly as argument of PHP functions
        $this->atomIs('Variablearray')
             ->codeIs($vars, true)
             ->inIs('VARIABLE')
             ->raw($safeIndex)
             ->goToArray()
             ->inIsIE('CODE')
             ->analyzerIs('Security/SensitiveArgument')
             ->inIs('ARGUMENT');
        $this->prepareQuery();

        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation!
        // $_GET/_POST array... inside a string is useless and safe (will print Array)
        // "$_GET/_POST ['index']"... inside a string or a concatenation is unsafe
        $this->atomIs('Variablearray')
             ->codeIs($vars, true)
             ->inIs('VARIABLE')
             ->raw($safeIndex)
             ->goToArray()
             ->inIsIE('CODE')
             ->inIs('CONCAT');
        $this->prepareQuery();

        // foreach (looping on incoming variables)
        $this->atomIs(self::$VARIABLES_ALL)
             ->codeIs($vars, true)
             ->goToArray()
             ->inIs('SOURCE');
        $this->prepareQuery();

    }
}

?>
