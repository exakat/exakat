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

class IndirectInjection extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Security/SensitiveArgument',
                     'Security/GPRAliases');
    }
    
    public function analyze() {
        $vars = $this->query('g.V().hasLabel("Analysis").has("analyzer","Analyzer\\\\Security\\\\GPRAliases").out("ANALYZED").values("fullcode").unique()');

        // Relayed via variable to sensitive function
        // $a = $_GET['a']; f($a); function f($a) { exec($a);}
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->_as('result')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->functionDefinition()
             ->inIs('NAME')

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
        // $a = $_GET['a']; exec($a);
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->analyzerIs('Security/SensitiveArgument')
             ->inIsIE('CODE')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        // $_GET/_POST array... inside a string is useless and safe (will print Array)
        // "$_GET/_POST ['index']"... inside a string or a concatenation is unsafe
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->inIs('CONCAT')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation!
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->inIs('VARIABLE')
             ->inIs('CONCAT')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // foreach (looping on incoming variables)
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->inIs('SOURCE');
        $this->prepareQuery();

    }
}

?>
