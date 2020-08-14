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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class IsZero extends Analyzer {
    public function analyze(): void {
        // $a = $c - $c;
        // $a = $c + $d - $c;
        // $a = $c + $d -$e - $c;
        // $a = $d + $c -$e - $c;
        $minus = $this->dictCode->translate(array('-'));

        if (empty($minus)) {
            return;
        }

        $MAX_LOOPING = self::MAX_LOOPING;
        $labels = array('Variable',
                        'Integer',
                        'Member',
                        'Float',
                        'Staticproperty',
                        'Array',
                        'Functioncall',
                        'Staticmethodcall',
                        'Methodcall',
                        );
        $labelsList = makeList($labels);

        $this->atomIs('Addition')
             ->hasNoParent('Addition', array('LEFT'))
             ->hasNoParent('Addition', array('RIGHT'))
             ->raw(<<<GREMLIN
sideEffect{x = [:]; id2 = it.get().id();}
.where(
   __.sideEffect{ previous = 1; supervious = 1;}
     .repeat(
       __.where( __.sideEffect{ if (it.get().value("code") == $minus[0]) { p = -1; } else { p = 1;}}.out("LEFT", "RIGHT").hasLabel("Addition").sideEffect{ previous = p;}.fold())
         .where( __.sideEffect{ if (it.get().value("code") == $minus[0]) { p = -1; } else { p = 1;}}.out("SIGN").sideEffect{ previous = p;}.fold())
         .where( __.sideEffect{ if (it.get().value("code") == $minus[0]) { p = -1; } else { p = 1;}}.out("LEFT", "RIGHT", "CODE", "SIGN").hasLabel("Assignation", "Parenthesis", "Sign").sideEffect{ previous = 1; supervious *= p;}.fold())
         .not(hasLabel("Multiplication"))
         .out("LEFT", "RIGHT", "CODE", "SIGN")
   )
    .emit().times($MAX_LOOPING)
    .hasLabel($labelsList).not(where( __.in("LEFT").hasLabel("Assignation")))
    .sideEffect{ v = it.get().value("fullcode");}

    .where(__.in("RIGHT").sideEffect{ if (it.get().value("token") == 'T_MINUS') { inc = -1; } else { inc = 1;} }.fold())
    .where(__.in("LEFT").not(where(__.in("RIGHT"))).sideEffect{ inc = 1;}.fold())
    .where(__.in("LEFT").in("RIGHT").sideEffect{ if (it.get().value("token") == 'T_MINUS') { inc = -1; } else { inc = 1;} }.fold())
    .where(__.in("SIGN").sideEffect{ inc = previous * supervious;}.fold())
    .where(__.in("CODE").sideEffect{ inc = supervious;}.fold())

    .sideEffect{ 
        if ((v =~ "-" ).getCount() != 0 ) {
            inc *=  -1;
        }
        
        v = v.replaceAll('\\\+', '').replaceAll('\\\-', ''); 
        
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
