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

class CouldBePrivateMethod extends Analyzer {
    public function dependsOn() {
        return array('Classes/MethodUsedBelow');
    }
    
    public function analyze() {
        // Searching for properties that are never used outside the definition class or its children

        // Non-static properties
        // Case of object->property (that's another public access)
$query = <<<GREMLIN
g.V().hasLabel("Methodcall")
     .not( where( __.out("OBJECT").hasLabel("This")) )
     .out("METHOD")
     .hasLabel("Methodcallname")
     .values("code")
     .unique()
GREMLIN;
        $publicMethods = $this->query($query)
                              ->toArray();
        
        $this->atomIs('Method')
             ->hasNoOut('PRIVATE')
             ->hasNoOut('STATIC')
             ->analyzerIsNot('Classes/MethodUsedBelow')
             ->outIs('NAME')
             ->codeIsNot($publicMethods, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();

        // Static properties
        // Case of property::property (that's another public access)
        $LOOPS = self::MAX_LOOPING;
        $query = <<<GREMLIN
g.V().hasLabel("Staticmethodcall")
     .out("CLASS")
     .hasLabel("Identifier", "Nsname")
     .as("classe")
     .sideEffect{ fns = it.get().value("fullnspath"); }
     .in("CLASS")
     .out("METHOD")
     .hasLabel("Methodcallname")
     .sideEffect{ name = it.get().value("code"); }
     .as("method")
     .repeat( __.inE().not(hasLabel("DEFINITION", "ANALYZED")).outV()).until( hasLabel("Class", "File") )
     .or( hasLabel("File"), 
        __.repeat( __.as("x").out("EXTENDS", "IMPLEMENTS").in("DEFINITION").where(neq("x")) ).emit().times($LOOPS)
          .where( __.out("METHOD").out("NAME").filter{ it.get().value("code") == name; } )
          .filter{it.get().value("fullnspath") != fns; }
        )
     .select("classe", "method").by("fullnspath").by("code")
     .unique()
GREMLIN;
        $publicStaticMethods = $this->query($query)
                                    ->toArray();
        
        if (!empty($publicStaticMethods)) {
            $calls = array();
            foreach($publicStaticMethods as $value) {
                if (isset($calls[$value['method']])) {
                    $calls[$value['method']][] = $value['classe'];
                } else {
                    $calls[$value['method']] = array($value['classe']);
                }
            }
            
            // Property that is not used outside this class or its children
            $this->atomIs('Method')
                 ->hasNoOut('PRIVATE')
                 ->hasOut('STATIC')
                 ->analyzerIsNot('Classes/MethodUsedBelow')
                 ->_as('results')
                 ->codeIsNot(array_keys($calls), self::NO_TRANSLATE)
                 ->savePropertyAs('code', 'variable')
                 ->goToClass()
                 ->isNotHash('fullnspath', $calls, 'variable')
                 ->back('results');
            $this->prepareQuery();
        }
    }
}

?>
