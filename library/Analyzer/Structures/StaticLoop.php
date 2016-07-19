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

class StaticLoop extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $nonDeterminist = $this->loadIni('php_nondeterministic.ini', 'functions');
        $nonDeterminist = $this->makeFullNsPath($nonDeterminist);
        $nonDeterminist = "'\\" . join("', '\\", $nonDeterminist)."'";

        $whereNonDeterminist = 'where( __.repeat( __.out() ).emit( hasLabel("Functioncall") ).times(15).hasLabel("Functioncall").where(__.in("METHOD", "NEW").count().is(eq(0))).has("token", within("T_STRING", "T_NS_SEPARATOR")).filter{ it.get().value("fullnspath") in ['.$nonDeterminist.']}.count().is(eq(0)) )';
        
        // foreach with only one value
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')

             // Check that blind variable are not mentionned 
             ->raw('where( __.repeat( __.out() ).emit( hasLabel("Variable") ).times(15).filter{ it.get().value("fullcode") == blind}.count().is(eq(0)) )')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist)
             ->back('first');
        $this->prepareQuery();

        // foreach with key value
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')

             ->outIs('KEY')
             ->savePropertyAs('fullcode', 'k')
             ->inIs('KEY')

             ->outIs('VALUE')
             ->savePropertyAs('code', 'v')
             ->inIs('VALUE')

             ->back('first')
             ->outIs('BLOCK')
             
             // Check that blind variables are not mentionned 
             ->raw('where( __.repeat( __.out() ).emit( hasLabel("Variable") ).times(15).filter{ it.get().value("fullcode") in [v, k]}.count().is(eq(0)) )')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist)
             ->back('first');
        $this->prepareQuery();
        // foreach with complex structures (property, static property, arrays, references... ?)

        // for
        $this->atomIs('For')
             ->outIs('INCREMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('For')
             // collect all variables in INCREMENT and INIT (ignore FINAL)
             ->raw('where( __.sideEffect{ blind = []}.out("INCREMENT", "INIT").repeat( out() ).emit( hasLabel("Variable")).times(15).sideEffect{ blind.push(it.get().value("code")); }.fold() )')
             
             ->outIs('BLOCK')
             // check if the variables are used here
             ->raw('where( __.repeat( __.out() ).emit( hasLabel("Variable") ).times(15).filter{ it.get().value("fullcode") in blind}.count().is(eq(0)) )')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist)

             // check if there are method call
             ->filter(' it.out().loop(1){true}{it.object.atom in ["Methodcall", "Staticmethodcall"]}.any() == false')

             ->back('first');
        $this->prepareQuery();

        // for with complex structures (property, static property, arrays, references... ?)

        // do...while
        $this->atomIs('Dowhile')
             // collect all variables
             ->raw('where( __.sideEffect{ blind = []}.out("CONDITION").repeat( out() ).emit( hasLabel("Variable")).times(15).sideEffect{ blind.push(it.get().value("code")); }.fold() )')

             ->outIs('BLOCK')
             // check if the variables are used here
             ->raw('where( __.repeat( __.out() ).emit( hasLabel("Variable") ).times(15).filter{ it.get().value("fullcode") in blind}.count().is(eq(0)) )')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist)

             // check if there are method call
             ->filter(' it.out().loop(1){true}{it.object.atom in ["Methodcall", "Staticmethodcall"]}.any() == false')

             ->back('first');
        $this->prepareQuery();

        // do while with complex structures (property, static property, arrays, references... ?)

        // while
        $this->atomIs('While')

             // collect all variables
             ->raw('where( __.sideEffect{ blind = []}.out("CONDITION").repeat( out() ).emit( hasLabel("Variable")).times(15).sideEffect{ blind.push(it.get().value("code")); }.fold() )')

             ->outIs('BLOCK')
             // check if the variables are used here
             ->raw('where( __.repeat( __.out() ).emit( hasLabel("Variable") ).times(15).filter{ it.get().value("fullcode") in blind}.count().is(eq(0)) )')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist)
             ->back('first');
        $this->prepareQuery();

        // while with complex structures (property, static property, arrays, references... ?)
    }
}

?>
