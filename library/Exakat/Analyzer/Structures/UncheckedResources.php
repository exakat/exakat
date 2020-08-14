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

class UncheckedResources extends Analyzer {
    // fread(fopen('path/to/file', 'r'));
    public function analyze(): void {
        $resourceUsage = $this->loadJson('resource_usage.json');

        $positions = array(0);
        foreach($resourceUsage as $creation => $usage) {
            $creation = makeFullNsPath($creation);
            foreach($positions as $pos) {
                $position = "function$pos";
                if (!isset($usage->{$position})) {
                    continue;
                }
                $functions = makeFullNsPath((array) $usage->{$position});

                //direct usage of the resource :
                // readdir(opendir('uncheckedDir4'));
                $this->atomFunctionIs($creation)
                     ->inIs('ARGUMENT')
                     ->atomIs('Functioncall')
                     ->fullnspathIs($functions);
                $this->prepareQuery();

                // deferred usage of the resource
                //$dir = opendir('uncheckedDir4'); readdir($dir);
                $this->atomFunctionIs($creation)
                     ->inIs('RIGHT')
                     ->atomIs('Assignation')
                     // checked with a if ($resource) or while($resource)
                     ->hasNoIn('CONDITION')
                     ->as('result')
                     ->outIs('LEFT')
                     ->atomIs(self::CONTAINERS)
                     ->savePropertyAs('fullcode', 'tmpvar')
                     ->inIs('LEFT')
                     ->nextSibling()
                     ->atomInsideNoBlock(self::CONTAINERS)
                     ->samePropertyAs('fullcode', 'tmpvar')

                     // checked with a is_resource
                     ->raw('not( where( __.in("ARGUMENT").has("fullnspath", "\\\\is_resource") ) )')
                     // checked with a !$variable
                     ->hasNoIn('NOT')

                     // checked as the condition in a if/then or while
                     ->hasNoParent(array('Ifthen', 'While'), array('CONDITION'))

                     // checked with a $variable &&
                     ->hasNoChildren('Logical', array('LEFT', 'RIGHT'))

                     // checked with a if ($resource == false) or while($resource == false)
                     ->hasNoComparison()

                     ->back('result');
                $this->prepareQuery();
            }
        }
    }
}

?>
