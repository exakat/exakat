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


namespace Analyzer\Structures;

use Analyzer;

class ImplicitGlobal extends Analyzer\Analyzer {
    public function analyze() {
        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');

        $globallyDeclared = $this->query('g.idx("atoms")[["atom":"Global"]].out("GLOBAL")
                                            .has("atom", "Variable")
                                            .filter{ it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}.any() == false}
                                            .code');

        // global $x (Global that is not declared as Global in the global space)
        $this->atomIs('Global')
             ->hasFunction()
             ->outIs('GLOBAL')
             ->tokenIs('T_VARIABLE')
             ->codeIsNot($superglobals)
             ->codeIsNot(array('$argv', '$argc'))
             ->codeIsNot($globallyDeclared);
        $this->prepareQuery();

        // $GLOBALS['x']
        $globallyDeclaredNoDollar = array_map(function($x) { return substr($x, 1); }, $globallyDeclared);
        $this->atomIs('Variable')
             ->code('$GLOBALS')
             ->hasFunction()
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->_as('results')
             ->outIs('INDEX')
             ->atomIs('String')
             ->noDelimiterIsNot($globallyDeclaredNoDollar)
             ->back('results');
        $this->prepareQuery();
    }
}

?>
