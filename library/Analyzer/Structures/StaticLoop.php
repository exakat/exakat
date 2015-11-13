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
        
        // foreach with only one value
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && it.object.fullcode == blind}.any() == false')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->filter(' it.out().loop(1){true}{it.object.atom == "Functioncall" && it.object.fullnspath in ['.$nonDeterminist.']}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // foreach with key value
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')

             ->outIs('KEY')
             ->savePropertyAs('fullcode', 'key')
             ->inIs('KEY')

             ->outIs('VALUE')
             ->savePropertyAs('code', 'value')
             ->inIs('VALUE')

             ->back('first')
             ->outIs('BLOCK')
             
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && (it.object.fullcode == key || it.object.fullcode == value)}.any() == false')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->filter(' it.out().loop(1){true}{it.object.atom == "Functioncall" && it.object.fullnspath in ['.$nonDeterminist.']}.any() == false')
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
             ->outIs('INCREMENT')
             // collect all variables
             ->raw('sideEffect{ blind = []; it.out().loop(1){true}{it.object.atom == "Variable"}.aggregate(blind){it.fullcode}.iterate(); }')
             ->inIs('INCREMENT')

             ->outIs('BLOCK')
             // check if the variables are used here
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && it.object.fullcode in blind}.any() == false')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->filter(' it.out().loop(1){true}{it.object.atom == "Functioncall" && it.object.fullnspath in ['.$nonDeterminist.']}.any() == false')

             ->back('first');
        $this->prepareQuery();

        // for with complex structures (property, static property, arrays, references... ?)

        // do...while
        $this->atomIs('Dowhile')
             ->outIs('CONDITION')
             // collect all variables
             ->raw('sideEffect{ blind = []; it.out().loop(1){true}{it.object.atom == "Variable"}.aggregate(blind){it.fullcode}.iterate(); }')
             ->inIs('CONDITION')
             ->outIs('BLOCK')
             // check if the variables are used here
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && it.object.fullcode in blind}.any() == false')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->filter(' it.out().loop(1){true}{it.object.atom == "Functioncall" && it.object.fullnspath in ['.$nonDeterminist.']}.any() == false')

             ->back('first');
        $this->prepareQuery();

        // do while with complex structures (property, static property, arrays, references... ?)

        // while
        $this->atomIs('While')
             ->outIs('CONDITION')
             // collect all variables
             ->raw('sideEffect{ blind = []; it.out().loop(1){true}{it.object.atom == "Variable"}.aggregate(blind){it.fullcode}.iterate(); }')
             ->inIs('CONDITION')
             ->outIs('BLOCK')
             // check if the variables are used here
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && it.object.fullcode in blind}.any() == false')

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->filter(' it.out().loop(1){true}{it.object.atom == "Functioncall" && it.object.fullnspath in ['.$nonDeterminist.']}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // while with complex structures (property, static property, arrays, references... ?)
    }
}

?>
