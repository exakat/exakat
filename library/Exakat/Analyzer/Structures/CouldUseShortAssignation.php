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

class CouldUseShortAssignation extends Analyzer {
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;
        
        // Commutative operation : Addition
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'receiver')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->raw(<<<GREMLIN
emit().repeat(__.coalesce( __.hasLabel("Addition").has("token", "T_PLUS").not(out("RIGHT", "LEFT").hasLabel("Arrayliteral")).out("RIGHT"),
                           __.hasLabel("Parenthesis").out("CODE"),
                           __.hasLabel("Assignation").out("RIGHT")
                           )
              ).times($MAX_LOOPING)
GREMLIN
)
             ->raw('coalesce( __.hasLabel("Addition").has("token", "T_PLUS").not(out("RIGHT", "LEFT").hasLabel("Arrayliteral")).out("LEFT"), __.filter{true; })')
             ->samePropertyAs('fullcode', 'receiver', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // Commutative operation : Multiplication
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'receiver')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->raw(<<<GREMLIN
emit().repeat(__.coalesce( __.hasLabel("Multiplication").has("token", "T_STAR").out("RIGHT"),
                           __.hasLabel("Parenthesis").out("CODE"),
                           __.hasLabel("Assignation").out("RIGHT")
                           )
              ).times($MAX_LOOPING)
GREMLIN
)
             ->raw('coalesce( __.hasLabel("Multiplication").has("token", "T_STAR").out("LEFT"), __.filter{true; })')
             ->samePropertyAs('fullcode', 'receiver', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // Non-Commutative operation
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'receiver')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->codeIs(array('-', '/', '%', '<<=', '>>=', '**', '&', '^', '|'))
             ->outIs('LEFT')
             ->samePropertyAs('fullcode', 'receiver')
             ->back('first');
        $this->prepareQuery();

        // Special case for .
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'receiver')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Concatenation')
             ->outWithRank('CONCAT', 0)
             ->samePropertyAs('fullcode', 'receiver')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
