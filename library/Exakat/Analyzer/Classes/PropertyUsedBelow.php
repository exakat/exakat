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

class PropertyUsedBelow extends Analyzer {
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;
        
        //////////////////////////////////////////////////////////////////
        // property + $this->property
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Ppp')
             ->isNot('static', true)
             ->outIs('PPP')
             ->_as('ppp')
             ->savePropertyAs('propertyname', 'propertyname')
             ->goToClass()
             ->raw(<<<GREMLIN
where( __.repeat( out("DEFINITION").in("EXTENDS") ).emit().times($MAX_LOOPING)
         .where( __.out("METHOD").out("BLOCK")
                   .repeat( __.out({$this->linksDown})).emit(hasLabel("Member")).times($MAX_LOOPING)
                   .where( __.out("OBJECT").hasLabel("This"))
                   .out("MEMBER").has("token", "T_STRING").filter{ it.get().value("code") == propertyname}
          )
)
GREMLIN
)
             ->back('ppp');
        $this->prepareQuery();

        //////////////////////////////////////////////////////////////////
        // static property : inside the self class
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Ppp')
             ->is('static', true)
             ->outIs('PPP')
             ->_as('ppp')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->raw(<<<GREMLIN
where( __.repeat( out("DEFINITION").in("EXTENDS") ).emit().times($MAX_LOOPING)
      .where( __.out("METHOD").out("BLOCK")
                .repeat( __.out({$this->linksDown})).emit(hasLabel("Staticproperty")).times($MAX_LOOPING)
                .out("MEMBER").has("token", "T_VARIABLE").filter{ it.get().value("code") == property}
      )
)
GREMLIN
)
             ->back('ppp');
        $this->prepareQuery();
        
        // This could be also checking for fnp : it needs to be a 'family' class check.
    }
}

?>
