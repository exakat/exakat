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

class StaticLoop extends Analyzer {
    public function analyze() {
        $methods = new Methods($this->config);
        $nonDeterminist = $methods->getNonDeterministFunctions();
        $nonDeterminist = "'\\\\" . implode("', '\\\\", $nonDeterminist)."'";

        $whereNonDeterminist = 'not( where( __.repeat( __.out('.$this->linksDown.') ).emit( ).times('.self::MAX_LOOPING.').hasLabel("Functioncall").has("token", within("T_STRING", "T_NS_SEPARATOR")).filter{ it.get().value("fullnspath") in ['.$nonDeterminist.']} ) )';
        
        $checkBlindVariable = 'not( where( __.repeat( __.out('.$this->linksDown.') ).emit( ).times('.self::MAX_LOOPING.').hasLabel("Variable", "Variableobject", "Variablearray").filter{ it.get().value("fullcode") in blind} ) )';
        
        // foreach with only one value
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->raw('sideEffect{ blind = []; blind.push(it.get().value("code"));}')
             ->back('first')
             ->outIs('BLOCK')

             // Check that blind variable are not mentionned
             ->raw($checkBlindVariable)

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist)
             ->back('first');
        $this->prepareQuery();

        // foreach with key value
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->raw('sideEffect{ blind = [];}')

             ->outIs('INDEX')
             ->raw('sideEffect{ blind.push(it.get().value("fullcode")); }')
             ->inIs('INDEX')

             ->outIs('VALUE')
             ->raw('sideEffect{ blind.push(it.get().value("code")); }')
             ->inIs('VALUE')

             ->back('first')
             ->outIs('BLOCK')
             
             // Check that blind variables are not mentionned
             ->raw($checkBlindVariable)

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
             ->raw('where( __.sideEffect{ blind = []}.out("INCREMENT", "INIT").repeat( out('.$this->linksDown.') ).emit().times('.self::MAX_LOOPING.').hasLabel("Variable", "Variableobject", "Variablearray").sideEffect{ blind.push(it.get().value("code")); }.fold() )')
             
             ->outIs('BLOCK')
             // check if the variables are used here
             ->raw($checkBlindVariable)

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist)

             ->back('first');
        $this->prepareQuery();

        // for with complex structures (property, static property, arrays, references... ?)

        // do...while
        $this->atomIs('Dowhile')
             // collect all variables
             ->raw('where( __.sideEffect{ blind = []}.out("CONDITION").repeat( out('.$this->linksDown.') ).emit( hasLabel("Variable")).times('.self::MAX_LOOPING.').sideEffect{ blind.push(it.get().value("code")); }.fold() )')

             ->outIs('BLOCK')
             // check if the variables are used here
             ->raw($checkBlindVariable)

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist)

             ->back('first');
        $this->prepareQuery();

        // do while with complex structures (property, static property, arrays, references... ?)

        // while
        $this->atomIs('While')

             // collect all variables
             ->raw('where( __.sideEffect{ blind = []}.out("CONDITION").repeat( out('.$this->linksDown.') ).emit( ).times('.self::MAX_LOOPING.').sideEffect{ blind.push(it.get().value("code")); }.fold() )')

             ->outIs('BLOCK')
             // check if the variables are used here
             ->raw($checkBlindVariable)

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist)
             ->back('first');
        $this->prepareQuery();

        // while with complex structures (property, static property, arrays, references... ?)
    }
}

?>
