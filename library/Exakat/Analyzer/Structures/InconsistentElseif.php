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

class InconsistentElseif extends Analyzer {
    public function analyze() {
        $MAX_LOOPING = self::MAX_LOOPING;
        
        // if ($a == 1) {} elseif ($b == 2) {}
        $this->atomIs('Ifthen')
             ->isNot('token', 'T_ELSEIF')
             ->outIs('CONDITION')
             ->collectContainers('containers')
             ->back('first')
             ->raw(<<<GREMLIN
repeat( __.out("ELSE")).emit().times($MAX_LOOPING).hasLabel("Ifthen")
GREMLIN
)
             ->outIs('CONDITION')
             ->collectContainers('containers2')
             ->filter('containers.intersect(containers2) == []')
             
             ->back('first');
        $this->prepareQuery();
    }
}

?>
