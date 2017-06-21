<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Data\Methods;

class ShouldPreprocess extends Analyzer {
    public function analyze() {
        $dynamicAtoms = array('Variable', 'Member', 'Magicconstant', 'Staticmethodcall', 'Staticproperty', 'Methodcall');
        //'Functioncall' : if they also have only constants.

        $methods = new Methods($this->config);
        $functionList = $methods->getDeterministFunctions();
        $functionList = $this->makeFullNsPath($functionList);

        // Operator only working on constants
        $tokenList = makeList( self::$FUNCTIONS_TOKENS );
        $this->atomIs(array('Addition', 'Multiplication', 'Concatenation', 'Power', 'Bitshift', 'Logical', 'Not'))
            // Functioncall, that are not authorized
             ->raw('where( __.repeat( out('.$this->linksDown.') ).emit( hasLabel("Functioncall").has("fullnspath") ).times('.self::MAX_LOOPING.')
                                             .hasLabel("Functioncall")
                                             .has("token", within('.$tokenList.'))
                                             .filter{ !(it.get().value("fullnspath") in ['.str_replace('\\', '\\\\', $this->SorA($functionList)).']) }.count().is(eq(0)) )')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();
        
        $functionListNoArray = array_diff($functionList,
                array('\\defined', '\\error_reporting', '\\extension_loaded', '\\get_defined_vars', '\\print', '\\echo', '\\set_time_limit'));
        
        // Function only applied to constants
        $this->atomFunctionIs($functionListNoArray)
             ->raw('where( __.out("ARGUMENTS").out("ARGUMENT").not(has("constant", true)).count().is(eq(0)) )');
        $this->prepareQuery();
    }
}

?>
