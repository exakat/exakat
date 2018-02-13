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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;
use Exakat\Tokenizer\Token;

class CouldBePrivate extends Analyzer {
    public function dependsOn() {
        return array('Classes/PropertyUsedBelow',
                    );
    }
    
    public function analyze() {
        // Searching for properties that are never used outside the definition class or its children

        // Non-static properties
        // Case of object->property (that's another public access)
$query = <<<GREMLIN
g.V().hasLabel("Member")
     .not( where( __.out("OBJECT").hasLabel("This")) )
     .out("MEMBER")
     .hasLabel("Name")
     .values("code")
     .unique()
GREMLIN;
        $publicProperties = $this->query($query)->toArray();

        if (!empty($publicProperties)) {
            $this->atomIs('Ppp')
                 ->hasNoOut('PRIVATE')
                 ->hasNoOut('STATIC')
                 ->outIs('PPP')
                 ->analyzerIsNot('Classes/PropertyUsedBelow')
                 ->isNot('propertyname', $publicProperties);
            $this->prepareQuery();
        }

        // Static properties
        // Case of property::property (that's another public access)
        $LOOPS = self::MAX_LOOPING;
        $query = <<<GREMLIN
g.V().hasLabel("Staticproperty")
     .out("CLASS")
     .as("classe")
     .has("fullnspath")
     .sideEffect{ fns = it.get().value("fullnspath"); }
     .in("CLASS")
     .out("MEMBER")
     .hasLabel("Variable")
     .sideEffect{ name = it.get().value("code"); }
     .as("property")
     .repeat( __.inE().not(hasLabel("DEFINITION", "ANALYZED")).outV()).until(hasLabel("Class", "File") )
     .or( hasLabel("File"), 
        __.repeat( __.as("x").out("EXTENDS", "IMPLEMENTS").in("DEFINITION").where(neq("x")) ).emit().times($LOOPS)
          .where( __.out("PPP").out("PPP").filter{ it.get().value("code") == name; } )
          .filter{it.get().value("fullnspath") != fns; }
        )
     .select("classe", "property").by("fullnspath").by("code")
     .unique()
GREMLIN;
        $publicStaticProperties = $this->query($query)->toArray();

        if (!empty($publicStaticProperties)) {
            $calls = array();
            foreach($publicStaticProperties as $value) {
                if (isset($calls[$value['property']])) {
                    $calls[$value['property']][] = $value['classe'];
                } else {
                    $calls[$value['property']] = array($value['classe']);
                }
            }
            
            // Property that is not used outside this class or its children
            $this->atomIs('Ppp')
                 ->hasNoOut('PRIVATE')
                 ->hasOut('STATIC')
                 ->outIs('PPP')
                 ->analyzerIsNot('Classes/PropertyUsedBelow')
                 ->_as('results')
                 ->outIsIE('LEFT')
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
