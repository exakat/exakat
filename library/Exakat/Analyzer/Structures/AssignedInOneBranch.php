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

class AssignedInOneBranch extends Analyzer {
    public function analyze() {
        $equal = $this->dictCode->translate(array('='));

        // if() {$b = 1; } else { }
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
             ->raw('not( where( __.out("ELSE").not(has("token", "T_ELSEIF")).emit( ).repeat( __.out() ).times('.self::MAX_LOOPING.').hasLabel("Assignation").has("token", "T_EQUAL").out("LEFT").hasLabel("Variable", "Staticproperty", "Member", "Array").filter{ it.get().value("fullcode").toLowerCase() == variable.toLowerCase()}) )')
             ->back('first');
        $this->prepareQuery();

        // if() {} else {$b = 1;  }
        $this->atomIs('Ifthen')
             ->isNot('token', 'T_ELSEIF')
             ->outIs('ELSE')
             ->atomInside('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs(self::$LITERALS)
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIs(self::$CONTAINERS)
             ->savePropertyAs('fullcode', 'variable')
             ->back('first')
             ->raw('not( where( __.out("THEN").not(has("token", "T_ELSEIF")).emit( ).repeat( __.out() ).times('.self::MAX_LOOPING.').hasLabel("Assignation").has("token", "T_EQUAL").out("LEFT").hasLabel("Variable", "Staticproperty", "Member", "Array").filter{ it.get().value("fullcode").toLowerCase() == variable.toLowerCase()}) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
