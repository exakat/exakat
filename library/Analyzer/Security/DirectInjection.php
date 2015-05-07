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


namespace Analyzer\Security;

use Analyzer;

class DirectInjection extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Security\\SensitiveArgument');
    }
    
    public function analyze() {
        $vars = $this->loadIni('php_incoming.ini');
        $vars = $vars['incoming'];

        // $_GET/_POST ... directly as argument of PHP functions
        $this->atomIs('Variable')
             ->code($vars)
             ->analyzerIs('Analyzer\\Security\\SensitiveArgument')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        // $_GET/_POST ['index'] (one level).. directly as argument of PHP functions
        $this->atomIs('Variable')
             ->code($vars)
             ->inIs('VARIABLE')
             ->analyzerIs('Analyzer\\Security\\SensitiveArgument')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        // $_GET/_POST ['index']['index2'] (2 levels and more)... directly as argument of PHP functions
        $this->atomIs('Variable')
             ->code($vars)
             ->raw('in("VARIABLE").loop(1){true}{it.object.atom == "Array"}')
             ->analyzerIs('Analyzer\\Security\\SensitiveArgument')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // $_GET/_POST array... inside a string is useless and safe (will print Array)
        // "$_GET/_POST ['index']"... inside a string or a concatenation is unsafe
        $this->atomIs('Variable')
             ->code($vars)
             ->raw('in("VARIABLE").loop(1){true}{ it.object.atom == "Array"}')
             ->inIs('CONCAT')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        $this->atomIs('Variable')
             ->code($vars)
             ->inIs('VARIABLE')
             ->inIs('CONCAT')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation!

        // foreach (looping on incoming variables)
        $this->atomIs('Variable')
             ->code($vars)
             ->raw('in("VARIABLE").loop(1){true}{ it.object.atom == "Array"}')
             ->inIs('SOURCE');
        $this->prepareQuery();
    }
}

?>
