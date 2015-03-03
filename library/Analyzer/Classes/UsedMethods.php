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


namespace Analyzer\Classes;

use Analyzer;

class UsedMethods extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\MarkCallable');
    }
    
    public function analyze() {
        $magicMethods = $this->loadIni('php_magic_methods.ini');
        $magicMethods = $magicMethods['magicMethod'];

        // Normal Methodcall
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"Methodcall"]].out("METHOD").filter{ it.code.toLowerCase() == method.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();

        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
            // call with call_user_func
             ->raw('filter{ g.idx("atoms")[["atom":"Functioncall"]].hasNot("fullnspath", null).has("fullnspath", "\\\\call_user_func").any() }')
             ->back('used');
        $this->prepareQuery();
        
        // Staticmethodcall
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"Staticmethodcall"]].out("METHOD").filter{ it.code.toLowerCase() == method.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();

        // the special methods must be processed independantly
        // __destruct is always used, no need to spot

        // method used statically in a callback with an array
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"Functioncall"]].has("token", "T_ARRAY").hasNot("cbClass", null).filter{ it.cbMethod == method.toLowerCase()}.filter{ it.cbClass == fullnspath.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();

        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
             ->raw('filter{ g.idx("atoms")[["atom":"String"]].hasNot("cbClass", null).filter{ it.cbMethod == method.toLowerCase()}.filter{ it.cbClass == fullnspath.toLowerCase()}.any()}')
             ->back('used');
        $this->prepareQuery();
    }
}

?>
