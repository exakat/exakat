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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class IsZero extends Analyzer {
    public function analyze() {
        // $a = $c - $c;
        // $a = $c + $d - $c;
        // $a = $c + $d -$e - $c;
        // $a = $d + $c -$e - $c;
        $minus = $this->dictCode->translate('-');

        if (empty($minus)) {
            return;
        }
        
        $MAX_LOOPING = self::MAX_LOOPING;
        $labels = array('Variable',
                        'Integer',
                        'Member',
                        'Real',
                        'Staticproperty',
                        'Array',
                        'Functioncall',
                        'Staticmethodcall',
                        'Methodcall',
                        );
        $labelsList = makeList($labels);
        
        $skip = 'coalesce( __.in("CODE"), __.in("RIGHT").hasLabel("Assignation"), __.filter{true})';
        $skip .= ".$skip";
        
        $this->atomIs('Addition')
             ->hasNoParent('Addition', array('LEFT'))
             ->hasNoParent('Addition', array('RIGHT'))
             ->raw(<<<GREMLIN
sideEffect{x = [:]; id2 = it.get().id();}
.where(
   __.repeat(
       __.out('LEFT', 'RIGHT', 'SIGN', 'CODE')
   )
    .emit().times($MAX_LOOPING)
    .hasLabel($labelsList)
    .where(
       __
        .sideEffect{ v = it.get().value('fullcode'); inc = 1; }
        .where( __.in('SIGN').hasLabel('Sign').has('code', $minus[0]).sideEffect{ inc = -1; }.fold())
        .where( __.$skip.in('RIGHT').hasLabel('Addition').has('code', $minus[0]).sideEffect{ inc *= -1; }.fold())
        .where( __.$skip.in('LEFT').hasLabel('Addition').$skip.in('RIGHT').hasLabel('Addition').has('code', $minus[0]).sideEffect{ inc *= -1; }.fold())

        .repeat(__.in('LEFT', 'RIGHT', 'SIGN', 'CODE')
                  .where( __.in('SIGN').hasLabel('Sign').has('code', $minus[0]).sideEffect{ inc *= -1; }.fold())
                  .where( __.in('LEFT').hasLabel('Addition').in('RIGHT').hasLabel('Addition').has('code', $minus[0]).sideEffect{ inc *= -1; }.fold())
        ).until(__.filter{ it.get().id() == id2;})

        .fold()
       )
    .sideEffect{ 
    if (x[v] == null) {
       x[v] = 0;
    }

    x[v] += inc; 

    }
    .fold()
)
.filter{ x.findAll{a,b -> b == 0} != [:]; }

GREMLIN
);
        $this->prepareQuery();
    }
}

?>
