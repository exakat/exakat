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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class MemoizeMagicCall extends Analyzer {
    public function analyze() {
        //

        $LINKS_DOWN = $this->linksDown;

        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('BLOCK')
             ->raw(<<<GREMLIN
sideEffect{ members = [:]; }.where(
    __.repeat( __.out($LINKS_DOWN)).emit()
      .hasLabel("Member").has('isRead', true).where( __.in("DEFINITION").hasLabel("Magicmethod").out("NAME").has("fullcode", "__get"))
      .sideEffect{ 
        m = it.get().value("fullcode");
        if (members[m] != null) {
          ++members[m]; 
        } else {
          members[m] = 1; 
        }
      }
      .fold()
)
GREMLIN
)
             ->atomInside('Member')
             ->filter('members[it.get().value("fullcode")] > 1;')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
