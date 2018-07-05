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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class MultipleReturn extends Analyzer {
    public function analyze() {
        // function foo() { if ($a ) { return 1; } else { return 2; }}
        $MAX_LOOPING = self::MAX_LOOPING;

        $this->atomIs(array('Function', 'Closure', 'Method', 'Magimethod'))
             ->hasNoInterface()
             ->raw(<<<GREMLIN
where( 
    __.repeat( 
        __.out({$this->linksDown}).not( hasLabel("Closure", "Classanonymous")) ).emit().times($MAX_LOOPING)
          .hasLabel("Return")
          .count().is(gt(1))
)
GREMLIN
);
        $this->prepareQuery();
    }
}

?>
