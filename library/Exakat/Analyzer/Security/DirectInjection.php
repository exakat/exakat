<?php
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

class DirectInjection extends Analyzer {
    public function dependsOn() {
        return array('Security/SensitiveArgument',
                    );
    }
    
    public function analyze() {
        $vars = $this->loadIni('php_incoming.ini')['incoming'];
        
        $server = $this->dictCode->translate('$_SERVER');
        if (empty($server)) {
            $server = -1; // This will always fail
        } else {
            $server = $server[0];
        }
        
        $safe = array('DOCUMENT_ROOT',
                      'REQUEST_TIME',
                      'REQUEST_TIME_FLOAT',
                      'SCRIPT_NAME',
                      'SERVER_ADMIN',
                      '_',
                      );
        $safeList = makeList($safe);
        $safeIndex = <<<GREMLIN
or( 
    __.hasLabel("Phpvariable"), 
    __.out("VARIABLE").not(has("code", $server)), 
    __.out("INDEX").hasLabel("String")
      .not(where(__.out("CONCAT") ) )
      .not(has("noDelimiter", within([ $safeList ])))
)
GREMLIN;

        // Relayed call to another function
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'ranked')
             ->raw($safeIndex)
             ->_as('result')
             ->outIsIE('VARIABLE')
             ->atomIs(self::$VARIABLES_ALL)
             ->codeIs($vars, self::TRANSLATE, self::CASE_SENSITIVE)
             ->back('first')

             ->functionDefinition()
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'ranked')

             ->savePropertyAs('code', 'varname')
             ->inIs('ARGUMENT')

             ->outIs('BLOCK')
             ->atomInsideNoDefinition(array('Functioncall', 'Print', 'Echo', 'Exit'))
             ->outIs('ARGUMENT')
             ->outIsIE('CODE')
             ->analyzerIs('Security/SensitiveArgument')
             ->atomIs(array('Variable', 'Variableobject'))
             ->samePropertyAs('code', 'varname')
             ->back('result');
        $this->prepareQuery();

        // $_GET/_POST ... directly as argument of PHP functions
        $this->atomIs('Phpvariable')
             ->codeIs($vars, self::TRANSLATE, self::CASE_SENSITIVE)
             ->inIs('VARIABLE')
             ->raw($safeIndex)
             ->goToArray()
             ->analyzerIs('Security/SensitiveArgument')
             ->inIsIE('CODE')
             ->inIs('ARGUMENT');
        $this->prepareQuery();

        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation!
        // $_GET/_POST array... inside a string is useless and safe (will print Array)
        // "$_GET/_POST ['index']"... inside a string or a concatenation is unsafe
        $this->atomIs('Phpvariable')
             ->codeIs($vars, self::TRANSLATE, self::CASE_SENSITIVE)
             ->inIs('VARIABLE')
             ->raw($safeIndex)
             ->goToArray()
             ->inIsIE('CODE')
             ->inIs('CONCAT');
        $this->prepareQuery();

        // foreach (looping on incoming variables)
        $this->atomIs(self::$VARIABLES_ALL)
             ->codeIs($vars, self::TRANSLATE, self::CASE_SENSITIVE)
             ->goToArray()
             ->inIs('SOURCE');
        $this->prepareQuery();

    }
}

?>
