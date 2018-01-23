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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class EmptyFunction extends Analyzer {
    public function dependsOn() {
        return array('Composer/IsComposerNsname');
    }
    
    public function analyze() {
        // standalone function : empty is empty. Same for closure.
        $this->atomIs(array('Function', 'Closure'))
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        // method : then, it should not overwrite a parent's method
        $this->atomIs('Method')
             ->hasClassTrait()
             ->hasNoOut('ABSTRACT')
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->is('count', 1)
             ->outIs('EXPRESSION')
             ->atomIs('Void')
             ->goToClass()

             // Ignore classes that are extension from a composer class
             ->raw('not( where( __.out("EXTENDS").repeat( __.coalesce(__.in("DEFINITION"), __.filter{true}).out("EXTENDS") ).emit().times('.self::MAX_LOOPING.')
                             .where( __.in("ANALYZED").has("analyzer", "Composer/IsComposerNsname") )
                             ) )')

             // Ignore methods that are overwriting a parent class, unless it is abstract or private
             ->raw('not( where( __.repeat( out("EXTENDS").in("DEFINITION") ).emit(hasLabel("Class") ).times('.self::MAX_LOOPING.')
                             .out("METHOD").hasLabel("Method")
                             .not( where( __.out("ABSTRACT", "PRIVATE") ) ) 
                             .out("NAME").filter{ it.get().value("lccode") == name}
                             )  )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
