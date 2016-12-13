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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UsedMethods extends Analyzer {
    public function dependsOn() {
        return array('Functions/MarkCallable');
    }

    public function analyze() {
        $magicMethods = $this->loadIni('php_magic_methods.ini', 'magicMethod');
        
        // Normal Methodcall
        $methods = $this->query('g.V().hasLabel("Methodcall").out("METHOD").has("token", "T_STRING").map{ it.get().value("code").toLowerCase(); }.unique()');
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->codeIs($methods)
             ->back('used');
        $this->prepareQuery();

        // Staticmethodcall
        $staticmethods = $this->query('g.V().hasLabel("Staticmethodcall").out("METHOD").has("token", "T_STRING").map{ it.get().value("code").toLowerCase(); }.unique()');
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->_as('used')
             ->outIs('NAME')
             ->codeIsNot($magicMethods)
             ->codeIs($staticmethods)
             ->back('used');
        $this->prepareQuery();

        $callables = $this->query(<<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", "Functions/MarkCallable").out("ANALYZED")
.not( hasLabel("Function") )
.map{
    // Strings
    if (it.get().label() == 'String') {
        if (it.get().value("noDelimiter") =~ /::/) {
            s = it.get().value("noDelimiter").split('::');
            s[1].toLowerCase();
        } else {
            it.get().value("noDelimiter").toLowerCase();
        }
    } else if (it.get().label() == 'Arguments') {
        it.get().vertices(OUT, "ARGUMENT").each{
            if (it.value("rank") == 1) {
                s = it.value("noDelimiter").toLowerCase();
            }
        }
        s;
    } else {
        it.get().value("noDelimiter").toLowerCase();
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
             ->codeIs($callables)
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
             ->codeIs('__construct')
             ->inIs('NAME')
             ->inIs('ELEMENT')
             ->atomInside('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->samePropertyAs('fullnspath', 'fullnspath')
             ->back('used');
        $this->prepareQuery();

        // Normal Constructors
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->hasNoOut('PRIVATE')
             ->_as('used')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->goToClass()
             ->outIs('DEFINITION')
             ->hasIn('NEW')
             ->back('used');
        $this->prepareQuery();

        // the special methods must be processed independantly
        // __destruct is always used, no need to spot
    }

}

?>
