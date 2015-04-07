<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Structures;

use Analyzer;

class UncheckedResources extends Analyzer\Analyzer {
    public function analyze() {
        $resourceUsage = $this->loadJson('resource_usage.json');

        $positions = array(0,1,2);
        foreach($resourceUsage as $creation => $usage) {
            foreach($positions as $pos) {
                if (!isset($usage->{"function$pos"})) { continue; }
                $functions = $this->makeFullNsPath((array) $usage->{"function$pos"});

                //direct usage of the resource : 
                // readdir(opendir('uncheckedDir4'));
                $this->atomFunctionIs($creation)
                     ->inIs('ARGUMENT')
                     ->inIs('ARGUMENTS')
                     ->fullnspath(array('\\readdir', '\\rewinddir', '\\closedir'));
                $this->prepareQuery();

                // deferred usage of the resource
                //$dir = opendir('uncheckedDir4'); readdir($dir);
                $this->atomFunctionIs($creation)
                     ->inIs('RIGHT')
                     ->atomIs('Assignation')
                     ->raw('filter{ it.in("CODE").in("CONDITION").any() == false }')
                     ->_as('result')
                     ->outIs('LEFT')
                     ->nextVariable('resource')
                     // checked with a is_resource
                     ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").has("fullnspath", "\\\\is_resource").any() == false }')
                     // checked with a !$variable
                     ->raw('filter{ it.in("NOT").any() == false }')
                     // checked with a if ($resource) or while($resource) 
                     ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").in("RIGHT").in("CODE").in("RIGHT").has("atom", "Comparison").in("CONDITION").any() == false }')
                     ->back('result');
                $this->prepareQuery();
            }
        }
    }
}

?>
