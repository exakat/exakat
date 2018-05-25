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

class AlternativeConsistenceByFile extends Analyzer {
    public function analyze() {
        $atoms = array('Ifthen', 'Foreach', 'For', 'Switch', 'While');
        $atomsList = "'".implode("', '", $atoms)."'";
        
        $MAX_LOOPING = self::$MAX_LOOPING;

        // $this->linksDown is important here.
        $this->atomIs('File')
             ->raw('sideEffect{
            normal = 0;
            alternative = 0;
            }')
            ->raw(<<<GREMLIN
where( 
    __
    .repeat( __.out({$this->linksDown})).emit().times($MAX_LOOPING).hasLabel($atomsList)
    .or( __.has("alternative").sideEffect{ alternative = alternative + 1; },
         __.sideEffect{ normal = normal + 1; })
    .fold()
    )
GREMLIN
)
            ->filter('normal > 0 && alternative > 0')
            ->back('first');
        $this->prepareQuery();
    }
}

?>
