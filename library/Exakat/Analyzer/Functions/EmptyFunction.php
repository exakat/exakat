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

class EmptyFunction extends Analyzer {
    public function dependsOn() {
        return array('Composer/IsComposerNsname',
                    );
    }
    
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;
        
        $emptyBody = 'not( where( __.out("EXPRESSION").not(hasLabel("Void", "Global", "Static"))) )';
        
        // standalone function : empty is empty. Same for closure.
        $this->atomIs(array('Function', 'Closure'))
             ->outIs('BLOCK')
             ->raw($emptyBody)
             ->back('first');
        $this->prepareQuery();

        // method : then, it should not overwrite a parent's method
        $this->atomIs(array('Method', 'Magicmethod'))
             ->hasClassTrait()
             ->isNot('abstract', true)
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->raw($emptyBody)
             ->goToClass()

             // Ignore classes that are extension from a composer class
             ->raw(<<<GREMLIN
not( 
    where( __.out("EXTENDS")
             .repeat( __.coalesce(__.in("DEFINITION"), __.filter{true}).out("EXTENDS") ).emit().times($MAX_LOOPING)
             .where( __.in("ANALYZED").has("analyzer", "Composer/IsComposerNsname") )
          ) 
)
GREMLIN
)

             // Ignore methods that are overwriting a parent class, unless it is abstract or private
             ->raw(<<<GREMLIN
not( 
    where( __.repeat( out("EXTENDS").in("DEFINITION") ).emit( hasLabel("Class") ).times($MAX_LOOPING)
             .out("METHOD").hasLabel("Method")
             .not( where( __.has("abstract", true) ) ) 
             .not( where( __.has("visibility", "private") ) ) 
             .out("NAME")
             .filter{ it.get().value("lccode") == name}
    )
)
GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
