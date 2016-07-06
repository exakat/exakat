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

class ShouldPreprocess extends Analyzer\Analyzer {
    public function analyze() {
        $dynamicAtoms = array('Variable', 'Property', 'Identifier', 'Magicconstant');
        //'Functioncall' : if they also have only constants.

        $functionList = $this->loadIni('inert_functions.ini', 'functions');
//        $functionList = '"\\\\' . implode('", "\\\\', $functionList['functions']). '"';
        
        $this->atomIs(array('Addition', 'Multiplication', 'Concatenation', 'Power', 'Bitshift', 'Logical', 'Not'))
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspathIsNot($functionList)
             ->back('first')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomFunctionIs(array('\\join', '\\explode', '\\implode', '\\split'))
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspathIsNot($functionList)
             ->back('first')
             ->outIs('ARGUMENTS')
             ->noAtomInside($dynamicAtoms)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
