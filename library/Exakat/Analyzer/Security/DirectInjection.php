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

class DirectInjection extends Analyzer {
    public function dependsOn(): array {
        return array('Security/SensitiveArgument',
                     'Modules/IncomingData',
                     'Php/SafePhpvars',
                    );
    }

    public function analyze() {
        $vars = $this->loadIni('php_incoming.ini')->incoming;

        // Relayed call to another function
        // foo($_GET)
        $this->atomIs('Phpvariable')
             ->inIsIE('VARIABLE')
             ->analyzerIsNot('Php/SafePhpvars')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->atomIs(self::FUNCTIONS_CALLS)
             ->as('result')

             ->inIs('DEFINITION')
             ->outWithRank('ARGUMENT', 'ranked')

             ->outIs('NAME')
             ->outIs('DEFINITION')

             ->analyzerIs('Security/SensitiveArgument')
             ->back('result');
        $this->prepareQuery();

        // $_GET/_POST ... directly as argument of PHP functions
        $this->atomIs('Phpvariable')
             ->inIsIE('VARIABLE')
             ->analyzerIsNot('Php/SafePhpvars')
             ->analyzerIs('Security/SensitiveArgument')
             ->goToInstruction(array_merge(self::FUNCTIONS_CALLS, array('Include', 'Print', 'Echo', 'Exit')));
        $this->prepareQuery();
/*
        // Other source of tainted data
        $this->analyzerIs('Modules/IncomingData')
             ->analyzerIs('Security/SensitiveArgument')
             ->inIsIE('CODE')
             ->inIs('ARGUMENT');
        $this->prepareQuery();*/

        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation!
        // $_GET/_POST array... inside a string is useless and safe (will print Array)
        // "$_GET/_POST ['index']"... inside a string or a concatenation is unsafe
        $this->atomIs('Phpvariable')
             ->inIsIE('VARIABLE')
             ->analyzerIsNot('Php/SafePhpvars')
             ->goToInstruction(array('String', 'Concatenation'));
        $this->prepareQuery();

/*
        // Other source of tainted data
        $this->analyzerIs('Modules/IncomingData')
             ->inIs('VARIABLE')
             ->raw($safeIndex)
             ->goToArray()
             ->inIsIE('CODE')
             ->inIs('CONCAT');
        $this->prepareQuery();
*/
        // foreach (looping on incoming variables)
        $this->atomIs('Phpvariable')
             ->inIsIE('VARIABLE')
             ->analyzerIsNot('Php/SafePhpvars')
             ->goToArray()
             ->inIs('SOURCE');
        $this->prepareQuery();
/*
        $this->analyzerIs('Modules/IncomingData')
             ->goToArray()
             ->inIs('SOURCE');
        $this->prepareQuery();
*/
    }
}

?>
