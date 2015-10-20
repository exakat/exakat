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
        return array('Functions/MarkCallable');
    }

    public function analyze() {
        $magicMethods = $this->loadIni('php_magic_methods.ini', 'magicMethod');
        
        // Normal Methodcall
        $methods = $this->query('g.idx("atoms")[["atom":"Methodcall"]].out("METHOD").transform{ it.code.toLowerCase(); }.unique()');
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->code($methods)
             ->back('used');
        $this->prepareQuery();

         // call with call_user_func (???)
        $staticMethodCallable = $this->query('g.idx("analyzers")[["analyzer":"Analyzer\\\\Functions\\\\MarkCallable"]].out.filter{ it.out("ARGUMENT").has("rank", 0).has("atom", "String").any() }.out("ARGUMENT").has("rank", 1).noDelimiter.unique()');
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->savePropertyAs('code', 'method')
             ->code($staticMethodCallable)
             ->back('used');
        $this->prepareQuery();
        
        // Staticmethodcall
        $staticmethods = $this->query('g.idx("atoms")[["atom":"Staticmethodcall"]].out("METHOD").transform{ it.code.toLowerCase(); }.unique()');
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->code($staticmethods)
             ->back('used');
        $this->prepareQuery();

        $callables = $this->query(<<<GREMLIN
g.idx("analyzers")[["analyzer":"Analyzer\\\\Functions\\\\MarkCallable"]].out.transform{
    // Strings
    if (it.atom == 'String') {
        if (it.noDelimiter =~ /::/) {
            s = it.noDelimiter.split('::');
            s[1].toLowerCase();
        } else {
            it.noDelimiter.toLowerCase();
        }
    } else if (it.atom == 'Arguments') {
        it.out('ARGUMENT').has('rank', 1).next().noDelimiter.toLowerCase();
    } else {
        it.fullcode;
    }
}

GREMLIN
);
        
        // method used statically in a callback with an array
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->code($callables)
             ->back('used');
        $this->prepareQuery();

        // Private constructors
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasOut('PRIVATE')
             ->_as('used')
             ->outIs('NAME')
             ->code('__construct')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->atomInside('New')
             ->outIs('NEW')
             ->samePropertyAs('fullnspath', 'fullnspath')
             ->back('used');
        $this->prepareQuery();

        // the special methods must be processed independantly
        // __destruct is always used, no need to spot

    }

}

?>
