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

class RegisterGlobals extends Analyzer {
    public function dependsOn() {
        return array('Variables/IsModified',
                    );
    }
    
    public function analyze() {
        $superGlobals = $this->loadIni('php_superglobals.ini', 'superglobal');
        
        // With a foreach
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->codeIs($superGlobals, self::TRANSLATE, self::CASE_SENSITIVE)
             ->inIs('SOURCE')
             ->outIs('VALUE')
             ->outIs('INDEX')
             ->savePropertyAs('code', 'k')
             ->inIs('INDEX')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->analyzerIs('Variables/IsModified')
             ->tokenIs('T_DOLLAR')
             ->outIs('NAME')
             ->samePropertyAs('code','k')
             ->back('first');
        $this->prepareQuery();

        // With extract and overwriting option
        $this->atomFunctionIs('\\extract')
             ->outWithRank('ARGUMENT', 0)
             ->codeIs($superGlobals, self::TRANSLATE, self::CASE_SENSITIVE)
             ->inIs('ARGUMENT')
             ->outWithRank('ARGUMENT', 1)
             // Lazy way to check for EXTR_IF_EXISTS, \EXTR_IF_EXISTS and | EXTR_REFS
             ->regexIs('fullcode', '(EXTR_OVERWRITE|EXTR_IF_EXISTS)')
             ->back('first');
        $this->prepareQuery();

        // With extract and default option (EXTR_OVERWRITE)
        $this->atomFunctionIs('\\extract')
             ->noChildWithRank('ARGUMENT', 1)
             ->outWithRank('ARGUMENT', 0)
             ->codeIs($superGlobals, self::TRANSLATE, self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // With parse_url and no final argument
        $this->atomFunctionIs('\\parse_str')
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        // With import_request_variables
        $this->atomFunctionIs('\\import_request_variables');
        $this->prepareQuery();
        
        // Other methods?
    }
}

?>
