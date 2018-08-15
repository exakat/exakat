<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class StaticLoop extends Analyzer {
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;
        
        $nonDeterminist = self::$methods->getNonDeterministFunctions();
        $nonDeterminist = makeFullnspath($nonDeterminist);

        $whereNonDeterminist = <<<GREMLIN
not( 
    where( 
        __.repeat( __.out({$this->linksDown}) ).emit( ).times($MAX_LOOPING)
          .hasLabel("Functioncall")
          .has("token", within("T_STRING", "T_NS_SEPARATOR"))
          .filter{ it.get().value("fullnspath") in ***} 
          ) 
    )
GREMLIN;
        
        $checkBlindVariable = <<<GREMLIN
not( 
    where( 
        __.repeat( __.out({$this->linksDown}) )
          .emit( ).times($MAX_LOOPING)
          .hasLabel("Variable", "Variableobject", "Variablearray")
          .filter{ it.get().value("code") in blind} 
        ) 
    )
GREMLIN;
        
        $collectVariables = <<<GREMLIN
where( 
    __.sideEffect{ blind = []}
      .out("CONDITION", "INCREMENT", "INIT", "VALUE")
      .emit( ).repeat( out({$this->linksDown}) ).times($MAX_LOOPING)
      .hasLabel("Variable", "Variableobject", "Variablearray")
      .sideEffect{ blind.push(it.get().value("code")); }
      .fold() 
)
GREMLIN;
        
        // foreach with only one value
        $this->atomIs('Foreach')
             ->raw($collectVariables)
             ->outIs('BLOCK')

             // Check that blind variable are not mentionned
             ->raw($checkBlindVariable)

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist, $nonDeterminist)
             ->back('first');
        $this->prepareQuery();

        // for
        $this->atomIs('For')
             ->outIs('INCREMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('For')
             // collect all variables in INCREMENT and INIT (ignore FINAL)
             ->raw($collectVariables)
             
             ->outIs('BLOCK')
             // check if the variables are used here
             ->raw($checkBlindVariable)

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist, $nonDeterminist)

             ->back('first');
        $this->prepareQuery();

        // for with complex structures (property, static property, arrays, references... ?)

        // do...while
        $this->atomIs('Dowhile')
             // collect all variables
             ->raw($collectVariables)

             ->outIs('BLOCK')
             // check if the variables are used here
             ->raw($checkBlindVariable)

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist, $nonDeterminist)

             ->back('first');
        $this->prepareQuery();

        // do while with complex structures (property, static property, arrays, references... ?)

        // while
        $this->atomIs('While')

             // collect all variables
             ->raw($collectVariables)

             ->outIs('BLOCK')
             // check if the variables are used here
             ->raw($checkBlindVariable)

             // check if there are non-deterministic function : calling them in a loop is non-static.
             ->raw($whereNonDeterminist, $nonDeterminist)
             ->back('first');
        $this->prepareQuery();

        // while with complex structures (property, static property, arrays, references... ?)
        
        // TODO : handle the case of compact
        // TODO : handle the case of localproperties used in the conditions : with a method call, they may be also updated
        // TODO : same for references
    }
}

?>
