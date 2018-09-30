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


namespace Exakat\Query\DSL;

use Exakat\Query\Query;

class NoAnalyzerInside extends DSL {
    public function run() {
        list($atoms, $analyzer) = func_get_args();

        $MAX_LOOPING = self::$MAX_LOOPING;
        $linksDown = self::$linksDown;
        $atomList = makeList(makeArray($atoms));

$gremlin = <<<GREMLIN
not( 
    where( __.emit( ).repeat( __.out({$linksDown}) ).times($MAX_LOOPING)
             .hasLabel($atomList)
             .has("fullnspath")
             .where( __.in("ANALYZED").has("analyzer", within(***)))
          )     
)
GREMLIN;
        return new Command($gremlin,
                           array($analyzer));
    }
}
?>
