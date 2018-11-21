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

class CouldBeClassConstant extends Analyzer {
    public function dependsOn() {
        return array('Classes/IsModified',
                     'Classes/LocallyUnusedProperty',
                    );
    }
    
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;
        
        $this->atomIs('Ppp')
             ->hasClass()

             ->isNot('visibility', array('private', 'protected'))

             ->outIs('PPP')
             ->analyzerIsNot('Classes/LocallyUnusedProperty')

             ->outIs('DEFAULT')
             ->atomIsNot(array('Null', 'Staticconstant'))
             ->inIs('DEFAULT')
             
             // Ignore null or static expressions in definitions.
             ->raw('not(where( __.out("RIGHT").hasLabel("Null", "Staticconstant") ) )')

             ->savePropertyAs('propertyname', 'name')

             ->savePropertyAs('code', 'staticName')
             ->goToClass()

             ->savePropertyAs('fullnspath', 'fnp')

                // usage as property with $this
             ->raw(<<<GREMLIN
not( __.out("METHOD")
       .where( __.repeat( __.out({$this->linksDown}) ).emit( ).times($MAX_LOOPING).hasLabel("Member")
                            .where( __.out("OBJECT").hasLabel("This") )
                            .where( __.out("MEMBER").filter{ it.get().value("code") == name } )
                            .where( __.in("ANALYZED").has("analyzer", "Classes/IsModified") )
         )
)
GREMLIN
)

                // usage as static property with (namespace, self or static)
             ->raw(<<<GREMLIN
not( 
    __.out("METHOD")
      .where( __.repeat( __.out({$this->linksDown}) ).emit( ).times($MAX_LOOPING).hasLabel("Staticproperty")
                .where( __.out("CLASS").has("fullnspath").filter{ it.get().value("fullnspath") == fnp } )
                .where( __.out("MEMBER").filter{ it.get().value("code") == staticName } )
                .where( __.in("ANALYZED").has("analyzer", "Classes/IsModified") )
        ) 
    )
GREMLIN
)

             ->back('first');
             
             // Exclude situations where property is used as an object or a resource (can't be class constant)
        $this->prepareQuery();
    }
}

?>
