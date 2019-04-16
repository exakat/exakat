<?php
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
use Exakat\Analyzer\Analyzer;

class FollowCalls extends DSL {
    public function run() : Command {
        $MAX_LOOPING = self::$MAX_LOOPING;

        // Coalesce is not supported
        return new Command(<<<GREMLIN
emit().repeat(
    __.out('NAME').out('DEFINITION').as('a').in('ARGUMENT').in('DEFINITION').out('ARGUMENT').as('b')
      .where('a', eq('b') ).by('rank')
).times($MAX_LOOPING)
GREMLIN
);
    }
}
?>
