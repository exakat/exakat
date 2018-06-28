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

class PropertyUsedInternally extends Analyzer {

    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;

        // property + $this->property
        $this->atomIs('Class')
            // collect all $this->property calls
             ->raw(<<<GREMLIN
sideEffect{x = [:];}
.where( __.out("METHOD", "MAGICMETHOD").out("BLOCK")
          .repeat(out()).emit().times($MAX_LOOPING).hasLabel("Member")
          .out("OBJECT").hasLabel("This").in("OBJECT") 
          .out("MEMBER") 
          .sideEffect{ x[it.get().value("code")] = 1;}
          .fold()
)
GREMLIN
)
             ->outIs('PPP')
             ->isNot('static', true)
             ->outIs('PPP')
             ->filter('it.get().value("propertyname") in x.keySet(); ');
        $this->prepareQuery();

        //////////////////////////////////////////////////////////////////
        // static property : inside the self class
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fnp')
            // collect all $this->property calls
             ->raw(<<<GREMLIN
sideEffect{x = [:];}
.where( __.out("METHOD", "MAGICMETHOD").out("BLOCK")
          .repeat(out()).emit().times($MAX_LOOPING).hasLabel("Staticproperty")
          .out("CLASS").has("fullnspath").filter{it.get().value("fullnspath") == fnp}.in("CLASS") 
          .out("MEMBER") 
          .sideEffect{ x[it.get().value("code")] = 1;}
          .fold()
)
GREMLIN
)
             ->outIs('PPP')
             ->is('static', true)
             ->outIs('PPP')
             ->filter('it.get().value("code") in x.keySet(); ')
             ->inIsIE('LEFT');
        $this->prepareQuery();

// Test for arrays ?

    }
}

?>
