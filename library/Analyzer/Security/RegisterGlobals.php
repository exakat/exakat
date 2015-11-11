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

class RegisterGlobals extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Variables/IsModified');
    }
    
    public function analyze() {
        $superGlobals = $this->loadIni('php_superglobals.ini', 'superglobal');
        
        // With a foreach
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->code($superGlobals)
             ->inIs('SOURCE')
             ->outIs('VALUE')
             ->outIs('KEY')
             ->savePropertyAs('code', 'key')
             ->inIs('KEY')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->analyzerIs('Variables/IsModified')
             ->tokenIs('T_DOLLAR')
             ->outIs('NAME')
             ->samePropertyAs('code','key')
             ->back('first');
        $this->prepareQuery();

        // With extract and overwriting option
        $this->atomFunctionIs('\\extract')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->code($superGlobals)
             ->inIs('ARGUMENT')
             ->outIs('ARGUMENT')
             ->is('rank', 1)
             // Lazy way to check for EXTR_IF_EXISTS, \EXTR_IF_EXISTS and | EXTR_REFS
             ->regex('fullcode', '(EXTR_OVERWRITE|EXTR_IF_EXISTS)') 
             ->back('first');
        $this->prepareQuery();

        // With extract and default option (EXTR_OVERWRITE)
        $this->atomFunctionIs('\\extract')
             ->outIs('ARGUMENTS')
             ->noChildWithRank(1)
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->code($superGlobals)
             ->back('first');
        $this->prepareQuery();

        // With parse_url and no final argument
        $this->atomFunctionIs('\\parse_str')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->noChildWithRank(1)
             ->back('first');
        $this->prepareQuery();

        // With import_request_variables
        $this->atomIs('Functioncall')
             ->fullnspath('\\import_request_variables');
        $this->prepareQuery();
        
        // Other methods?
    }
}

?>
