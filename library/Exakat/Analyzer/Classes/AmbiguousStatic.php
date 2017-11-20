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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class AmbiguousStatic extends Analyzer {
    public function analyze() {
        // Methods with the same name, but with static or not.
        $query = <<<GREMLIN
g.V().hasLabel("Method").where( __.out("STATIC")).values('code').collect{it.toLowerCase(); }.unique()
GREMLIN;
        $staticMethod = $this->query($query)->toArray();

        // Global are unused if used only once
        $query = <<<GREMLIN
g.V().hasLabel("Method").not(where( __.out("STATIC"))).values('code').collect{it.toLowerCase(); }.unique()
GREMLIN;
        $normalMethod = $this->query($query)->toArray();

        $mixedMethod = array_values(array_intersect($normalMethod, $staticMethod));
        
        if (!empty($mixedMethod)){
            $this->atomIs('Method')
                 ->codeIs($mixedMethod);
            $this->prepareQuery();
        }

        // Properties with the same name, but with static or not.
        // Just like methods, they are case-insensitive, because static $X and $x are still ambiguous
        $query = <<<GREMLIN
g.V().hasLabel("Propertydefinition").as('property')
     .coalesce( __.in('LEFT'), __.filter{true; } )
     .in('PPP')
     .where( __.out("STATIC"))
     .select('property').values('code').collect{it.toLowerCase(); }.unique()
GREMLIN;
        $staticProperty = $this->query($query)->toArray();

        // Global are unused if used only once
        $query = <<<GREMLIN
g.V().hasLabel("Propertydefinition").as('property')
     .coalesce( __.in('LEFT'), __.filter{true; } )
     .in('PPP')
     .not(where( __.out("STATIC")))
     .select('property').values('code').collect{it.toLowerCase(); }.unique()
GREMLIN;
        $normalProperty = $this->query($query)->toArray();

        $mixedProperty = array_values(array_intersect($normalProperty, $staticProperty));
        
        if (empty($mixedProperty)){
            return ;
        }

        $this->atomIs('Propertydefinition')
             ->codeIs($mixedProperty);
        $this->prepareQuery();
    }
}

?>
