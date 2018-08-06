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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class UnusedArguments extends Analyzer {
    public function dependsOn() {
        return array('Variables/IsRead',
                     'Variables/IsModified',
                     );
    }
    
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;
        $isNotRead = <<<GREMLIN
not( 
    where( repeat( out({$this->linksDown}) ).emit( hasLabel("Variable", "Variablearray", "Variableobject").filter{ it.get().value("code") == varname; })
                                             .times($MAX_LOOPING)
      .where( __.in("ANALYZED").has("analyzer", "Variables/IsRead") )
    ) 
)
GREMLIN;
    
        $isNotUsed = <<<GREMLIN
not( 
    where( 
        repeat( out({$this->linksDown}) ).emit( hasLabel("Variable").filter{ it.get().value("code") == varname; } )
                                         .times($MAX_LOOPING) 
    ) 
)
GREMLIN;

        // Arguments, not reference, function
        $this->atomIs('Parameter')
             ->isNot('reference', true)
             ->outIs('NAME')
             ->savePropertyAs('code', 'varname')
             ->back('first')
             ->inIs('ARGUMENT')
             ->atomIs(self::$FUNCTIONS_ALL)
             ->_as('results')
             ->hasNoClassInterfaceTrait()
             ->outIs('BLOCK')
             // this argument must be read at least once
             ->raw($isNotRead)
             ->back('results');
        $this->prepareQuery();

        // Arguments, not reference, method (class, trait)
        $this->atomIs('Parameter')
             ->isNot('reference', true)
             ->outIs('NAME')
             ->savePropertyAs('code', 'varname')
             ->back('first')
             ->inIs('ARGUMENT')
             ->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIsNot('self')
             ->_as('results')

             ->hasClassTrait()
             ->isNot('abstract', true)
             ->checkInheriting()
             ->outIs('BLOCK')
             // this argument must be read at least once
             ->raw($isNotRead)
             ->back('results');
        $this->prepareQuery();

        // Arguments, reference, function
        $this->atomIs('Parameter')
             ->is('reference', true)
             ->outIs('NAME')
             ->savePropertyAs('code', 'varname')
             ->back('first')
             ->inIs('ARGUMENT')
             ->atomIs(self::$FUNCTIONS_ALL)
             ->_as('results')
             ->analyzerIsNot('self')
             ->hasNoClassInterfaceTrait()
             ->outIs('BLOCK')
             // this argument must be read or written at least once (in fact, used)
             ->raw($isNotUsed)
             ->back('results');
        $this->prepareQuery();

        // Arguments, reference, method
        $this->atomIs('Parameter')
             ->is('reference', true)
             ->outIs('NAME')
             ->savePropertyAs('code', 'varname')
             ->back('first')
             ->inIs('ARGUMENT')
             ->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIsNot('self')
             ->_as('results')

             ->hasClassTrait()
             ->isNot('abstract', true)
             ->checkInheriting()
             ->outIs('BLOCK')
             // this argument must be read or written at least once (in fact, used)
             ->raw($isNotUsed)
             ->back('results');
        $this->prepareQuery();

        // Arguments in a USE, not a reference
        $this->atomIs('Closure')
             ->analyzerIsNot('self')
             ->outIs('USE')
             ->isNot('reference', true)
             ->savePropertyAs('code', 'varname')
             ->back('first')

             ->outIs('BLOCK')
             // this argument must be read or written at least once
             ->raw($isNotRead)

             ->back('first');
        $this->prepareQuery();

        // Arguments in a USE, reference
        $this->atomIs('Closure')
             ->analyzerIsNot('self')
             ->outIs('USE')
             ->is('reference', true)
             ->savePropertyAs('code', 'varname')
             ->back('first')

             ->outIs('BLOCK')
             // this argument must be read or written at least once
             ->raw($isNotUsed)
             ->back('first');
        $this->prepareQuery();
    }
    
    private function checkInheriting() {
        $MAX_LOOPING = self::MAX_LOOPING;

        $this->_as('method')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->goToClassTrait()
             ->raw(<<<GREMLIN
not( 
    where( 
        repeat( __.as("x").out("EXTENDS", "IMPLEMENTS").in("DEFINITION").where(neq("x")) ).emit()
                                                                                          .times($MAX_LOOPING)
                          .out("METHOD")
                          .hasLabel("Method", "Magicmethod")
                          .out("NAME")
                          .filter{ it.get().value("code") == name}
    )
)
GREMLIN
)
             ->back('method');

        return $this;
    }
}

?>
