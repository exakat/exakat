<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
    public function dependsOn() {
        return array('Security/SensitiveArgument',
                     'Security/GPRAliases',
                    );
    }
    
    public function analyze() {
        $query = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer","Security/GPRAliases")
     .out("ANALYZED")
     .values("fullcode").unique()
GREMLIN;
        $vars = $this->query($query)->toArray();
        
        if (empty($vars)) {
            return;
        }

        // Relayed via variable to sensitive function
        // $a = $_GET['a']; f($a); function f($a) { exec($a);}
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENT')
             ->savePropertyAs('rank', 'rank')
             ->_as('result')
             ->outIsIE('VARIABLE')
             ->atomIs(self::$VARIABLES_ALL)
             ->codeIs($vars, true)
             ->back('first')

             ->functionDefinition()
             ->inIs('NAME')

             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')

             ->savePropertyAs('code', 'varname')
             ->inIs('ARGUMENT')

             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->outIs('ARGUMENT')
             ->analyzerIs('Security/SensitiveArgument')
             ->outIsIE('CODE')
             ->atomIs(self::$VARIABLES_ALL)
             ->samePropertyAs('code', 'varname')
             ->back('result');
        $this->prepareQuery();

        // $_GET/_POST ... directly as argument of PHP functions
        // $a = $_GET['a']; exec($a);
        $this->atomIs('Variable')
             ->codeIs($vars, true)
             ->analyzerIs('Security/SensitiveArgument')
             ->inIsIE('CODE')
             ->inIs('ARGUMENT');
        $this->prepareQuery();

        // $_GET/_POST array... inside a string is useless and safe (will print Array)
        // "$_GET/_POST ['index']"... inside a string or a concatenation is unsafe
        $this->atomIs(self::$VARIABLES_ALL)
             ->codeIs($vars, true)
             ->inIs('CONCAT');
        $this->prepareQuery();

        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation!
        $this->atomIs('Variablearray')
             ->codeIs($vars, true)
             ->inIs('VARIABLE')
             ->inIs('CONCAT');
        $this->prepareQuery();

        // foreach (looping on incoming variables)
        $this->atomIs(self::$VARIABLES_ALL)
             ->codeIs($vars, true)
             ->inIs('SOURCE');
        $this->prepareQuery();

    }
}

?>
