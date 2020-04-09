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


class NoCodeInside extends DSL {
    public function run(): Command {
        list($atom, $values) = func_get_args();

        $atomFilter = makeList(makeArray($atom));

        assert($this->assertAtom($atom));
        $MAX_LOOPING = self::$MAX_LOOPING;
        $linksDown = self::$linksDown;

        // $fullcode is a name of a variable
        $gremlin = <<<GREMLIN
not(
    __.where( 
        __.repeat( __.out({$linksDown})).emit().times($MAX_LOOPING)
          .hasLabel($atomFilter)
          .filter{ it.get().value("code") in $values; }
    )
)

GREMLIN;
        return new Command($gremlin);
    }
}
?>
