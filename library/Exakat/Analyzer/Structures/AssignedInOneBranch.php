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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class AssignedInOneBranch extends Analyzer {
    public function analyze() {
        $this->atomIs('Ifthen')
             ->isNot('token', 'T_ELSEIF')
             ->hasOut('ELSE')
             ->outIs('THEN')
             ->atomInside('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs(self::$LITERALS)
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIs(self::$CONTAINERS)
             ->savePropertyAs('fullcode', 'variable')
             ->back('first')
             ->raw('not( __.out("ELSE").not(has("token", "T_ELSEIF")).emit( hasLabel("Assignation")).repeat( out('.$this->linksDown.') ).times('.self::MAX_LOOPING.').hasLabel("Assignation").filter{it.get().value("code").toLowerCase() == "="}.out("LEFT").hasLabel("Variable", "Staticproperty", "Member", "Array").filter{ it.get().value("fullcode").toLowerCase() == variable.toLowerCase()})')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
