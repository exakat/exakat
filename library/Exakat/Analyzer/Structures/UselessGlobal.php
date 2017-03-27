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

class UselessGlobal extends Analyzer {
    public function dependsOn() {
        return array('Variables/VariableUsedOnceByContext',
                     'Structures/UnusedGlobal');
    }
    
    public function analyze() {

        // Global are unused if used only once
        $inglobal = $this->query(<<<GREMLIN
g.V().hasLabel("Global").out("GLOBAL").values("code")
GREMLIN
);

        $inGLobals = $this->query(<<<'GREMLIN'
g.V().hasLabel("Variable").has("code", "\$GLOBALS").in("VARIABLE").hasLabel("Array").values("globalvar")
GREMLIN
);

        $counts = array_count_values(array_merge($inGLobals, $inglobal));
        $loneGlobal = array_filter($counts, function ($x) { return $x == 1; });
        $loneGlobal = array_keys($loneGlobal);
        

        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->codeIs($loneGlobal);
        $this->prepareQuery();

        $this->atomIs('Variable')
             ->codeIs('$GLOBALS')
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->_as('results')
             ->is('globalvar', $loneGlobal)
             ->back('results');
        $this->prepareQuery();

        // used only once

        // written only
    }
}

?>
