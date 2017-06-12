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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UncheckedResources extends Analyzer {
    public function analyze() {
        $resourceUsage = $this->loadJson('resource_usage.json');

        $positions = array(0);
        foreach($resourceUsage as $creation => $usage) {
            foreach($positions as $pos) {
                if (!isset($usage->{"function$pos"})) {
                    continue;
                }
                $functions = $this->makeFullNsPath((array) $usage->{"function$pos"});
                
                //direct usage of the resource :
                // readdir(opendir('uncheckedDir4'));
                $this->atomFunctionIs($creation)
                     ->inIs('ARGUMENT')
                     ->inIs('ARGUMENTS')
                     ->hasNoIn('METHOD')
                     ->fullnspathIs($functions);
                $this->prepareQuery();

                // deferred usage of the resource
                //$dir = opendir('uncheckedDir4'); readdir($dir);
                $this->atomFunctionIs($creation)
                     ->inIs('RIGHT')
                     ->atomIs('Assignation')
                     // checked with a if ($resource) or while($resource)
                     ->hasNoIn('CONDITION')
                     ->_as('result')
                     ->outIs('LEFT')
                     ->savePropertyAs('fullcode', 'tmpvar')
                     ->inIs('LEFT')
                     ->nextSibling()
                     ->atomInside('Variable')
                     ->samePropertyAs('code', 'tmpvar')

                     // checked with a is_resource
                     ->raw('where( __.in("ARGUMENT").in("ARGUMENTS").has("fullnspath", "\\\\is_resource").count().is(eq(0)) )')
                     // checked with a !$variable
                     ->hasNoIn('NOT')

                     // checked as the condition in a if/then or while
                     ->raw('where( __.in("CONDITION").hasLabel("Ifthen", "While" ).count().is(eq(0)) )')

                     // checked with a $variable &&
                     ->raw('where( __.in("LEFT", "RIGHT").hasLabel("Logical").count().is(eq(0)) )')
                     
                     // checked with a if ($resource == false) or while($resource == false)
                     ->hasNoComparison()
                    // ->raw('where( __.in("ARGUMENT").in("ARGUMENTS").in("RIGHT").in("CODE").in("RIGHT").has("atom", "Comparison").in("CONDITION").count().is(eq(0)) )')
                     ->back('result');
                $this->prepareQuery();
            }
        }
    }
}

?>
