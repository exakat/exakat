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

class ThisIsNotAnArray extends Analyzer {

    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;

        // direct class
        $this->atomIs('This')
             ->inIs(array('VARIABLE', 'APPEND'))
             ->_as('results')
             ->atomIs(array('Array', 'Arrayappend'))
             // class may be \ArrayAccess
             ->goToClass()
             ->raw('not( where( __.out("IMPLEMENTS").has("fullnspath", "\\\\arrayaccess") ) )')
             ->raw(<<<GREMLIN
not( 
    where( __.repeat( __.out("IMPLEMENTS", "EXTENDS").in("DEFINITION")).emit().times($MAX_LOOPING)
                        .out("IMPLEMENTS").has("fullnspath", "\\\\arrayaccess") ) 
    )
GREMLIN
)
             ->back('results');
        $this->prepareQuery();
    }
}

?>
