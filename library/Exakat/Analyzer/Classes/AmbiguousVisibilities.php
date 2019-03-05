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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class AmbiguousVisibilities extends Analyzer {
    public function analyze() {
        // Properties with the same name, but with different visibility
        $query = <<<GREMLIN
g.V().hasLabel("Ppp")
     .as("visibility")
     .out("PPP")
     .as("ppp")
     .select("ppp", "visibility").by("code").by("visibility")
     .unique()
GREMLIN;
        $visibilities = $this->query($query)->toArray();
        
        $mixed = array();
        foreach($visibilities as $case) {
            $mixed[$case['ppp']][$case['visibility'] === 'none' ? 'public' : $case['visibility']] = 1;
        }
        $mixed = array_filter($mixed, function($x) { return count($x) > 1;});
        $mixedProperty = array_keys($mixed);

        if (!empty($mixedProperty)){
            $this->atomIs('Propertydefinition')
                 ->codeIs($mixedProperty, self::NO_TRANSLATE, self::CASE_SENSITIVE);
            $this->prepareQuery();
        }
    }
}

?>
