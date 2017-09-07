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

class MethodUsedBelow extends Analyzer {
    public function analyze() {
        //////////////////////////////////////////////////////////////////
        // property + $this->property
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Method')
             ->hasNoOut('STATIC')
             ->outIs('NAME')
             ->savePropertyAs('code', 'methodname')
             ->goToClass()
             ->raw('where( __.repeat( out("DEFINITION").in("EXTENDS") ).emit().times('.self::MAX_LOOPING.')
                             .where( __.out("METHOD").out("BLOCK")
                                       .repeat( __.out()).emit().times('.self::MAX_LOOPING.').hasLabel("Methodcall")
                                       .out("OBJECT").has("code", "\\$this").in("OBJECT")
                                       .out("METHOD").has("token", "T_STRING").filter{ it.get().value("code").toLowerCase() == methodname.toLowerCase()}
                              )
                         )')
             ->back('first');
        $this->prepareQuery();

        //////////////////////////////////////////////////////////////////
        // static property : inside the self class
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Method')
             ->hasOut('STATIC')
             ->outIs('NAME')
             ->savePropertyAs('code', 'methodname')
             ->goToClass()
             ->raw('where( __.repeat( out("DEFINITION").in("EXTENDS") ).emit().times('.self::MAX_LOOPING.')
                             .where( __.out("METHOD").out("BLOCK")
                                       .repeat( __.out('.$this->linksDown.')).emit().times('.self::MAX_LOOPING.').hasLabel("Staticmethodcall")
                                       .out("MEMBER").has("token", "T_STRING").filter{ it.get().value("code").toLowerCase() == methodname.toLowerCase()}
                              )
                         )')
             ->back('ppp');
        $this->prepareQuery();
        
        // This could be also checking for fnp : it needs to be a 'family' class check.
    }
}

?>
