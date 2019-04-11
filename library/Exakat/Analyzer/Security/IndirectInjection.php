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

class IndirectInjection extends Analyzer {
    public function dependsOn() {
        return array('Security/SensitiveArgument',
                     'Security/GPRAliases',
                    );
    }
    
    public function analyze() {
        // Relayed via variable to sensitive function
        // function f() {  $a = $_GET['a'];exec($a);}
        $this->atomIs(self::$CALLS)
             ->outIs('ARGUMENT')
             ->analyzerIs('Security/SensitiveArgument')
             ->outIsIE('VARIABLE')
             ->atomIs(self::$VARIABLES_ALL)
             ->filter(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('DEFINITION')
                     ->analyzerIs('Security/GPRAliases')
             )
             ->back('first');
        $this->prepareQuery();

        // Relayed via argument to sensitive function
        //  function f($_GET['a']) {  exec($a);}
        $this->atomIs(self::$CALLS)
             ->outIsIE('METHOD')
             ->outIs('ARGUMENT')
             ->filter(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('DEFINITION')
                     ->analyzerIs('Security/GPRAliases')
             )
             ->savePropertyAs('rank', 'ranked')
             ->back('first')

             ->inIs('DEFINITION')

             ->outWithRank('ARGUMENT', 'ranked')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->analyzerIs('Security/SensitiveArgument')
             ->back('first');
        $this->prepareQuery();
        //function f() {  $a = $_GET['a'];exec($a);}

        // $_GET/_POST array... inside a string is useless and safe (will print Array)
        // "$_GET/_POST ['index']"... inside a string or a concatenation is unsafe
        $this->atomIs(self::$VARIABLES_ALL)
             ->filter(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('DEFINITION')
                     ->analyzerIs('Security/GPRAliases')
             )
             ->inIs('CONCAT');
        $this->prepareQuery();

        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation!
        $this->atomIs('Variablearray')
             ->filter(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('DEFINITION')
                     ->analyzerIs('Security/GPRAliases')
             )
             ->inIs('VARIABLE')
             ->inIs('CONCAT');
        $this->prepareQuery();

        // foreach (looping on incoming variables)
        $this->atomIs(self::$VARIABLES_ALL)
             ->filter(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('DEFINITION')
                     ->analyzerIs('Security/GPRAliases')
             )
             ->inIs('SOURCE');
        $this->prepareQuery();
    }
}

?>
