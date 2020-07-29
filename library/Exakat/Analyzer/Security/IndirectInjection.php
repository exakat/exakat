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

class IndirectInjection extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                     'Complete/PropagateCalls',
                     'Complete/SetClassMethodRemoteDefinition',
                     'Complete/SetClassRemoteDefinitionWithLocalNew',
                     'Complete/CreateDefaultValues',
                     'Security/SensitiveArgument',
                    );
    }

    public function analyze() : void {
        // Relayed via variable to sensitive function
        // function f() {  $a = $_GET['a'];exec($a);}
        $this->atomIs(self::FUNCTIONS_USAGE)
             ->outIs('ARGUMENT')
             ->analyzerIs('Security/SensitiveArgument')
             ->atomInsideNoDefinition(self::VARIABLES_ALL)
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->outIsIE('VARIABLE')
             ->atomIs('Phpvariable')
             ->back('first');
        $this->prepareQuery();

        // Relayed via argument to sensitive function
        //  function f($_GET['a']) {  exec($a);}
        $this->atomIs('Phpvariable')
             ->inIsIE('VARIABLE')
             ->followValue(5)
             ->analyzerIs('Security/SensitiveArgument')
             ->inIs('ARGUMENT')
             ->analyzerIsNot('self');
        $this->prepareQuery();
        //function f() {  $a = $_GET['a'];exec($a);}

        // $_GET/_POST array... inside a string is useless and safe (will print Array)
        // "$_GET/_POST ['index']"... inside a string or a concatenation is unsafe
        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation!
        $this->atomIs(self::VARIABLES_ALL)
             ->analyzerIsNot('self')
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->outIsIE('VARIABLE')
             ->atomIs('Phpvariable')
             ->back('first')
             ->inIsIE('VARIABLE')
             ->inIs('CONCAT');
        $this->prepareQuery();

        // foreach (looping on incoming variables)
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomInsideNoDefinition(self::VARIABLES_ALL)
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->outIsIE('VARIABLE')
             ->atomIs('Phpvariable')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
