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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ForeachReferenceIsNotModified extends Analyzer {
    public function dependsOn() {
        return array('Variables/IsModified',
                    );
    }
    
    public function analyze() {
        $modifiedVar = 'where( __.repeat( out('.$this->linksDown.') ).emit( hasLabel("Variable", "Variablearray", "Variableobject") ).times('.self::MAX_LOOPING.')
                                 .hasLabel("Variable", "Variablearray", "Variableobject")
                                 .filter{ it.get().value("code") == name}
                                 .where( __.in("ANALYZED").has("analyzer", "Variables/IsModified").count().is(eq(1)) )
                                 .count().is(eq(0)) )';

        // case of a variable
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('RIGHT')
             ->is('reference', true)
             ->savePropertyAs('code', 'name')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->raw($modifiedVar)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
