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
use Exakat\Data\Dictionary;

class AmbiguousStatic extends Analyzer {
    public function analyze() {
        // Methods with the same name, but with static or not.
        $query = <<<GREMLIN
g.V().hasLabel("Method").has("static", true).values("code").unique()
GREMLIN;
        $staticMethod = $this->query($query)->toArray();
        $staticMethod = $this->dictCode->source($staticMethod);
        $staticMethod = array_map('mb_strtolower', $staticMethod);
        $staticMethod = array_unique($staticMethod);

        // Global are unused if used only once
        $query = <<<GREMLIN
g.V().hasLabel("Method").not(has("static", true)).values("code").unique()
GREMLIN;
        $normalMethod = $this->query($query)->toArray();
        $normalMethod = $this->dictCode->source($normalMethod);
        $normalMethod = array_map('mb_strtolower', $normalMethod);
        $normalMethod = array_unique($normalMethod);

        $mixedMethod = array_values(array_intersect($normalMethod, $staticMethod));
        $mixedMethod = $this->dictCode->translate($mixedMethod, Dictionary::CASE_INSENSITIVE);
        
        if (!empty($mixedMethod)){
            $this->atomIs('Method')
                 ->codeIs($mixedMethod, self::NO_TRANSLATE);
            $this->prepareQuery();
        }

        // Properties with the same name, but with static or not.
        // Just like methods, they are case-insensitive, because static $X and $x are still ambiguous
        $query = <<<GREMLIN
g.V().hasLabel("Propertydefinition").as("property")
     .coalesce( __.in("LEFT"), __.filter{true; } )
     .in("PPP")
     .has("static", true)
     .select("property").values("code").unique()
GREMLIN;
        $staticProperty = $this->query($query)->toArray();

        // Global are unused if used only once
        $query = <<<GREMLIN
g.V().hasLabel("Propertydefinition").as("property")
     .coalesce( __.in("LEFT"), __.filter{true; } )
     .in("PPP")
     .not(has("static", true))
     .select("property").values("code").unique()
GREMLIN;
        $normalProperty = $this->query($query)->toArray();

        $mixedProperty = array_values(array_intersect($normalProperty, $staticProperty));
        
        if (empty($mixedProperty)){
            return ;
        }

        $this->atomIs('Propertydefinition')
             ->codeIs($mixedProperty, self::NO_TRANSLATE);
        $this->prepareQuery();
    }
}

?>
