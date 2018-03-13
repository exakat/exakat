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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class PropertyCouldBeLocal extends Analyzer {
    public function analyze() {
        // normal property
        $this->atomIs('Propertydefinition')
             ->inIs('LEFT')
             ->savePropertyAs('propertyname', 'member')
             ->inIs('PPP')
             ->hasNoOut(array('STATIC', 'PUBLIC', 'PROTECTED'))
             ->inIs('PPP')
             ->raw(<<<GREMLIN
where(
    __.out("METHOD")
      .not( where( __.out('STATIC') ) )
      .where( __.out("BLOCK")
          .repeat( __.out()).emit().times(5).hasLabel("Member").out("MEMBER").filter{ it.get().value("code") == member}
        )
        .count().is(eq(1))
)
GREMLIN
)
            ->back('first');
        $this->prepareQuery();

        // static property
        $this->atomIs('Propertydefinition')
             ->savePropertyAs('code', 'member')
             ->inIs('LEFT')
             ->inIs('PPP')
             ->hasOut('STATIC')
             ->hasNoOut(array('PUBLIC', 'PROTECTED'))
             ->inIs('PPP')
             ->raw(<<<GREMLIN
where(
    __.out("METHOD")
      .where( __.out('STATIC') )
      .where( __.out("BLOCK")
                .repeat( __.out()).emit().times(5).hasLabel("Staticproperty").out("MEMBER").filter{ it.get().value("code") == member}
       )
      .count().is(eq(1))
)
GREMLIN
)
            ->back('first');
        $this->prepareQuery();
    }
}

?>
