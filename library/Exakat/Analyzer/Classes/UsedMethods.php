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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UsedMethods extends Analyzer {
    public function analyze() {
        $magicMethods = $this->loadIni('php_magic_methods.ini', 'magicMethod');
        
        // Normal Methodcall
        $methods = $this->query(<<<GREMLIN
g.V().hasLabel("Methodcall").out("METHOD").has("token", "T_STRING").values("fullnspath").unique()
GREMLIN
)->toArray();

        if (!empty($methods)) {
            $this->atomIs('Method')
                 ->_as('used')
                 ->outIs('NAME')
                 ->codeIsNot($magicMethods)
                 ->codeIs($methods)
                 ->back('used');
            $this->prepareQuery();
        }

        // Staticmethodcall
        $staticmethods = $this->query(<<<GREMLIN
g.V().hasLabel("Staticmethodcall").out("METHOD").has("token", "T_STRING").values("fullnspath").unique()
GREMLIN
)->toArray();

        if (!empty($staticmethods)) {
            $this->atomIs('Method')
                 ->_as('used')
                 ->outIs('NAME')
                 ->codeIsNot($magicMethods)
                 ->codeIs($staticmethods)
                 ->back('used');
            $this->prepareQuery();
        }

        // Staticmethodcall in arrays
        // non-staticmethodcall in arrays, with $this
        $callablesStrings = $this->query(<<<GREMLIN
g.V().hasLabel("String")
     .where(__.in("DEFINITION"))
     .not(where(__.in("CONCAT")))
     .filter{ (it.get().value('noDelimiter') =~ "::." ).getCount() != 0 }
.map{
    // Strings
    if (it.get().label() == 'String') {
        if (it.get().value("noDelimiter") =~ /::/) {
            s = it.get().value("noDelimiter").split('::');
            s[1].toLowerCase();
        } else {
            it.get().value("noDelimiter").toLowerCase();
        }
    } else if (it.get().label() == 'Arrayliteral') {
        it.get().vertices(OUT, "ARGUMENT").each{
            if (it.value("rank") == 1) {
                s = it.value("noDelimiter").toLowerCase();
            }
        }
        s;
    } else {
        it.get().value("noDelimiter").toLowerCase();
    }
}.unique();

GREMLIN
        )->toArray();

        $callablesArray = $this->query(<<<GREMLIN
g.V().hasLabel("Arrayliteral")
     .where(__.values("count").is(eq(2)) )
     .where(__.out("ARGUMENT").has("rank", 0).hasLabel("String").in("DEFINITION"))
     .out("ARGUMENT")
     .has("rank", 1)
     .hasLabel("String")
     .has("noDelimiter")
     .values("noDelimiter")
     .unique();
GREMLIN
        )->toArray();

        $callablesThisArray = $this->query(<<<GREMLIN
g.V().hasLabel("Arrayliteral")
     .where(__.values("count").is(eq(2)) )
     .where(__.out("ARGUMENT").has("rank", 0).hasLabel("This"))
     .out("ARGUMENT")
     .has("rank", 1)
     .hasLabel("String")
     .has("noDelimiter")
     .values("noDelimiter")
     .unique();
GREMLIN
        )->toArray();

        $callables = array_unique(array_merge($callablesArray, $callablesThisArray, $callablesStrings));
        
        if (!empty($callables)) {
            $callables = array_map('strtolower', $callables);
            // method used statically in a callback with an array
            $this->atomIs('Method')
                 ->outIs('NAME')
                 ->codeIsNot($magicMethods)
                 ->codeIs($callables, self::TRANSLATE, self::CASE_INSENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }
        
        // Private constructors
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('METHOD')
             ->atomIs('Method')
             ->hasOut('PRIVATE')
             ->_as('used')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->back('first')
             ->outIs('METHOD')
             ->atomInside('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->samePropertyAs('fullnspath', 'fullnspath')
             ->back('used');
        $this->prepareQuery();

        // Normal Constructors
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fullnspath')
             ->outIs('METHOD')
             ->atomIs('Method')
             ->hasNoOut('PRIVATE')
             ->_as('used')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->back('first')
             ->outIs('DEFINITION')
             ->hasIn('NEW')
             ->back('used');
        $this->prepareQuery();

        // the special methods must be processed independantly
        // __destruct is always used, no need to spot
    }
}

?>
