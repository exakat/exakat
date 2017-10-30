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

class UsedClass extends Analyzer {
    public function dependsOn() {
        return array('Functions/MarkCallable');
    }
    
    public function analyze() {

        $new = $this->query(<<<GREMLIN
g.V().hasLabel("New").out("NEW").not(has("fullnspath", "")).values("fullnspath").unique()
GREMLIN
)->toArray();

        // class used in a New
        if (!empty($new)) {
            $this->atomIs('Class')
                 ->savePropertyAs('fullnspath', 'classdns')
                 ->fullnspathIs($new);
            $this->prepareQuery();
        }
        
        // classed used in a extends
        $extends = $this->query(<<<GREMLIN
g.V().hasLabel("Class").out("EXTENDS", "IMPLEMENTS").not(has("fullnspath", "")).values("fullnspath").unique()
GREMLIN
)->toArray();
        if (!empty($extends)) {
            $this->atomIs('Class')
                 ->savePropertyAs('fullnspath', 'classdns')
                 ->fullnspathIs($extends);
            $this->prepareQuery();
        }
        
        // class used in static property
        $staticproperties = $this->query(<<<GREMLIN
g.V().hasLabel("Staticproperty", "Staticconstant", "Staticmethodcall", "Instanceof").out("CLASS").not(has("fullnspath", "")).values("fullnspath").unique()
GREMLIN
)->toArray();
        if (!empty($staticproperties)) {
            $this->atomIs('Class')
                 ->savePropertyAs('fullnspath', 'classdns')
                 ->fullnspathIs($staticproperties);
            $this->prepareQuery();
        }
        
        // class used in a typehint
        $typehints = $this->query(<<<GREMLIN
g.V().hasLabel("Function").out("ARGUMENT").out("TYPEHINT").not(has("fullnspath", "")).values("fullnspath").unique()
GREMLIN
)->toArray();

        if (!empty($typehints)) {
            $this->atomIs('Class')
                 ->savePropertyAs('fullnspath', 'classdns')
                 ->fullnspathIs($typehints);
            $this->prepareQuery();
        }
        
        // class used in a Use
        $uses = $this->query(<<<GREMLIN
g.V().hasLabel("Use").out("USE").values("origin").unique()
GREMLIN
)->toArray();
        if (!empty($uses)) {
            $this->atomIs('Class')
                 ->fullnspathIs($uses);
            $this->prepareQuery();
        }

        // class used in a String (full string only)
        $strings = $this->query(<<<GREMLIN
g.V().hasLabel("String").has("token", "T_CONSTANT_ENCAPSED_STRING")
     .not(where( __.in("ARGUMENT").hasLabel("Arrayliteral") ) )
     .filter{ it.get().value("noDelimiter").length() < 100}.filter{ it.get().value("noDelimiter").length() > 0}
     .filter{ (it.get().value("noDelimiter") =~ /[^a-zA-Z0-9_\\x7f-\\xff]/).getCount() == 0}
     .map{ it.get().value("noDelimiter").toLowerCase(); }.unique()
GREMLIN
)->toArray();
        if (!empty($strings)) {
            $this->atomIs('Class')
                 ->outIs('NAME')
                 ->codeIs($strings)
                 ->back('first');
            $this->prepareQuery();
        }

        // class used in a String (string with ::)
        $strings = $this->query(<<<GREMLIN
g.V().hasLabel("String").not( where( __.out("CONCAT") ) )
                        .filter{ (it.get().value("noDelimiter") =~ "::" ).getCount() == 1 }
                        .where( __.in("ANALYZED").has("analyzer", "Functions/MarkCallable") )
                        .map{ it.get().value("noDelimiter").substring(0, it.get().value("noDelimiter").indexOf("::") );}.unique();
GREMLIN
)->toArray();
        $strings = $this->makeFullNsPath($strings);
        if (!empty($strings)) {
            $this->atomIs('Class')
                 ->fullnspathIs($strings)
                 ->back('first');
            $this->prepareQuery();
        }

        // class used in an array
        $arrays = $this->query(<<<GREMLIN
g.V().hasLabel("Functioncall").out("ARGUMENT")
        .hasLabel("Arrayliteral")
        .where( __.in("ANALYZED").has("analyzer", "Functions/MarkCallable") )
        .has("count", 2).out("ARGUMENT").has("rank", 0).values("noDelimiter").unique()
GREMLIN
)->toArray();
        $arrays = $this->makeFullNsPath($arrays);
        
        if (!empty($arrays)) {
            $this->atomIs('Class')
                 ->fullnspathIs($arrays)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
