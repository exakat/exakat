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


namespace Analyzer\Structures;

use Analyzer;

class ShouldPreprocess extends Analyzer\Analyzer {
    public function analyze() {
        $dynamicAtoms = array('Variable', 'Property', 'Identifier', 'Magicconstant');
        //'Functioncall',
        
        $functionList = $this->loadIni('inert_functions.ini');
        $functionList = '"' . implode('", "\\\\', $functionList['functions']). '"';
        
        $this->atomIs('Addition')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Multiplication')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Concatenation')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Bitshift')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Logical')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Not')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\join', '\\explode', '\\implode', '\\split'))
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();
    }
}

?>
