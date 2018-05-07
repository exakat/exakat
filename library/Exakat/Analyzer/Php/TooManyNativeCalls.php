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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class TooManyNativeCalls extends Analyzer {
    protected $nativeCallCounts = 3;
    
    public function dependsOn() {
        return array('Functions/IsExtFunction',
                    );
    }
    
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;

        $this->atomIs('Sequence')
             ->outIs('EXPRESSION')
             ->atomIsNot(array('Assignation',
                               'Case',
                               'Catch',
                               'Class',
                               'Classanonymous',
                               'Closure',
                               'Concatenation',
                               'Default',
                               'Dowhile',
                               'Finally',
                               'For',
                               'Foreach',
                               'Function',
                               'Ifthen',
                               'Include',
                               'Method',
                               'Namespace',
                               'Php',
                               'Return',
                               'Switch',
                               'Trait',
                               'Try',
                               'While',
                               ))
             ->_as('results')
             ->raw(<<<GREMLIN
where(
    __.emit( ).repeat( __.out().not(hasLabel("Closure", "Classanonymous")) ).times($MAX_LOOPING).hasLabel('Functioncall')
      .where( __.in("ANALYZED").has("analyzer", "Functions/IsExtFunction"))
      .count().is(gt($this->nativeCallCounts))
)
GREMLIN
)
             ->back('results');
        $this->prepareQuery();
    }
}

?>
