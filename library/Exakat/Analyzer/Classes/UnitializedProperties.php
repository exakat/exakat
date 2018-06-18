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

class UnitializedProperties extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor',
                     'Classes/IsModified',
                    );
    }
    
    public function analyze() {
        // Normal Properties (with constructor)
        $this->atomIs(self::$CLASSES_ALL)
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->isNot('static', true)
             ->outIs('PPP')
             ->atomIsNot('Assignation')
             ->_as('results')
             ->savePropertyAs('propertyname', 'property')
             ->back('first')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs(array('Method', 'Magicmethod'))
             ->analyzerIs('Classes/Constructor')
             ->raw('not(where(
    __.out("BLOCK").repeat( out('.$this->linksDown.') ).emit( ).times('.self::MAX_LOOPING.')
                   .hasLabel("Member")
                   .where( __.out("MEMBER").has("token", "T_STRING").filter{ it.get().value("code") == property} )
                   .where( __.in("ANALYZED").has("analyzer", "Classes/IsModified") )
                   ))')
             ->back('results');
        $this->prepareQuery();

        // without constructor
        $this->atomIs(self::$CLASSES_ALL)
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->isNot('static', true)
             ->outIs('PPP')
             ->atomIsNot('Assignation')
             ->_as('results')
             ->savePropertyAs('propertyname', 'property')
             ->back('first')
             ->raw('not( where( __.out("MAGICMETHOD").hasLabel("Magicmethod").in("ANALYZED").has("analyzer", "Classes/Constructor") ) )')
             ->back('results');
        $this->prepareQuery();

        // Static Properties (with constructor)
        $this->atomIs(self::$CLASSES_ALL)
             ->savePropertyAs('fullnspath', 'classe')
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('static', true)
             ->outIs('PPP')
             ->atomIsNot('Assignation')
             ->_as('results')
             ->savePropertyAs('code', 'property')
             ->back('first')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->analyzerIs('Classes/Constructor')
             ->raw('where(
    __.out("BLOCK").repeat( out('.$this->linksDown.') ).emit().times('.self::MAX_LOOPING.')
      .hasLabel("Staticproperty")
      .where( __.out("CLASS").has("fullnspath").filter{ it.get().value("fullnspath") == classe} )
      .where( __.out("MEMBER").filter{ it.get().value("code") == property}  )
      .where( __.in("ANALYZED").has("analyzer", "Classes/IsModified") ).count().is(eq(0))
)')
             ->back('results');
        $this->prepareQuery();

        $this->atomIs(self::$CLASSES_ALL)
             ->savePropertyAs('fullnspath', 'classe')
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('static', true)
             ->outIs('PPP')
             ->atomIsNot('Assignation')
             ->_as('results')
             ->savePropertyAs('code', 'property')
             ->back('first')
             ->raw('not( where( __.out("MAGICMETHOD").hasLabel("Magicmethod").in("ANALYZED").has("analyzer", "Classes/Constructor") ) )')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
