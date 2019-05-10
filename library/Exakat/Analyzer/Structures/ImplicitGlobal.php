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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ImplicitGlobal extends Analyzer {
    public function analyze() {
        // no Global $x;
        // function foo() { global $x; }
        $this->atomIs('Global')
             ->isGlobalCode()
             ->outIs('GLOBAL')
             ->values('code')
             ->unique();
        $globalGlobal = $this->rawQuery()
                             ->toArray();

        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');

        // can't bail out here : if $globalGlobal is empty, no global was declared outside functions.
        // This is still useful

        $this->atomIs('Global')
             ->hasFunction()
             ->outIs('GLOBAL')
             ->tokenIs('T_VARIABLE')
             ->codeIsNot($superglobals, self::TRANSLATE, self::CASE_SENSITIVE)
             ->codeIsNot($globalGlobal, self::NO_TRANSLATE, self::CASE_SENSITIVE);
        $this->prepareQuery();

        // Those are variables in the global space,
        $this->atomIs(self::$VARIABLE_USER)
             ->hasNoIn('GLOBAL')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->inIs('DEFINITION')
                             ->atomIs('Globaldefinition')
                     )
             )
             ->isGlobalCode()
             ->tokenIs('T_VARIABLE');
        $this->prepareQuery();
    }
}

?>
