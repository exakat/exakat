<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class CouldBeProtectedMethod extends Analyzer {
    public function analyze() {
        // Case of property->property (that's another public access)
        $query = <<<GREMLIN
g.V().hasLabel("Methodcall")
     .not( __.where( __.repeat( __.out("OBJECT")).emit().hasLabel("This") ) )
     .out("METHOD")
     .hasLabel("Methodcallname")
     .values("lccode")
     .unique()
GREMLIN;
        $publicMethods = $this->query($query)->toArray();

        // Member that is not used outside this class or its children
        $this->atomIs('Method')
             ->isNot('visibility', array('protected', 'private'))
             ->isNot('static', true)
             ->hasClass()
             ->outIs('NAME')
             ->codeIsNot($publicMethods, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();

        // Case of class::methodcall (that's another public access)
        $publicUsage = $this->query(<<<GREMLIN
g.V().hasLabel("Staticmethodcall").as("init")
     .out("CLASS").hasLabel("Identifier", "Nsname").as("classe")
     .sideEffect{ fnp = it.get().value("fullnspath") }
     .in("CLASS")
     .where( __.repeat( __.in($this->linksDown)).until(hasLabel("Class", "Classanonymous", "File"))
               .or(hasLabel("File"), 
                   hasLabel("Class", "Classanonymous").filter{ it.get().values("fullnspath") == fnp; }) 
           )
     .out("METHOD").hasLabel("Methodcallname").as("method")
     .select("classe", "method").by("fullnspath").by("lccode")
     .unique();
GREMLIN
)->toArray();
        
        $publicStaticMethods = array();
        foreach($publicUsage as $value) {
            if (isset($publicStaticMethods[$value['classe']])) {
                $publicStaticMethods[$value['classe']][] = $value['method'];
            } else {
                $publicStaticMethods[$value['classe']] = array($value['method']);
            }
        }

        if (!empty($publicStaticMethods)) {
            // Member that is not used outside this class or its children
            $this->atomIs('Method')
                 ->isNot('visibility', array('protected', 'private'))
                 ->is('static', true)
                 ->goToClass()
                 ->savePropertyAs('fullnspath', 'fnp')
                 ->back('first')
                 ->outIs('NAME')
                 ->isNotHash('lccode', $publicStaticMethods, 'fnp')
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
