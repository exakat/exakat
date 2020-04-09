<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class AtomInsideWithCall extends DSL {
    public function run(): Command {
        list($atoms) = func_get_args();

        assert($this->assertAtom($atoms));
        $diff = $this->normalizeAtoms($atoms);
        if (empty($diff)) {
            return new Command(Query::STOP_QUERY);
        }

        $linksDown = self::$linksDown;
        $MAX_LOOPING  = self::$MAX_LOOPING;

        $gremlin = <<<GREMLIN
emit( ).repeat( __.out($linksDown)
                  .coalesce( __.hasLabel("Functioncall").in("DEFINITION").out("BLOCK").out("EXPRESSION"), 
                             __.filter{true})
                  .not(hasLabel("Closure", "Classanonymous", "Function", "Class", "Trait", "Interface")) 
            )
            .times($MAX_LOOPING)
            .hasLabel(within(***))
GREMLIN;
        return new Command($gremlin, array($diff));
    }
}
?>
