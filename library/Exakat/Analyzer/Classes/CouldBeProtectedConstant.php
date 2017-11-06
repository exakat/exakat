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
use Exakat\Tokenizer\Token;

class CouldBeProtectedConstant extends Analyzer {
    public function analyze() {
        // Searching for properties that are never used outside the definition class or its children

        // global static constants : the one with no definition class : they are all ignored.
        $query = <<<GREMLIN
g.V().hasLabel("Staticconstant")
     .not( __.where( __.out("CLASS").in("DEFINITION")) )
     .out("CONSTANT")
     .hasLabel("Name")
     .values("code")
     .unique()
GREMLIN;
        $publicUndefinedConstants = $this->query($query)
                                         ->toArray();

        $notUsedOutside = <<<GREMLIN
not( __.where( __.out("DEFINITION")
         .in("CLASS")
         .hasLabel("Staticconstant")
         .out("CONSTANT")
         .filter{ it.get().value("code") == name; }
         .not( where( 
            __.repeat(__.inE().not(hasLabel("DEFINITION", "ANALYZED")).outV() ).until(hasLabel("File")).emit()
              .hasLabel("Class").filter{ it.get().value("fullnspath") == theClass }
          ) ) )
)
GREMLIN;

        // global static constants : the one with no definition class : they are all ignored.
        $this->atomIs('Constant')
             ->outIs('NAME')
             ->codeIsNot($publicUndefinedConstants)
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->inIs('CONST')
             ->hasNoOut(array('PRIVATE', 'PROTECTED'))
             ->inIs('CONST')
             ->savePropertyAs('fullnspath', 'theClass')
             ->raw($notUsedOutside)
             ->back('first');
    }
}

?>
