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
        //readdir(opendir('uncheckedDir4'));
        $this->atomFunctionIs('opendir')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->fullnspath(array('\\readdir', '\\rewinddir', '\\closedir'));
        $this->prepareQuery();

        //$dir = opendir('uncheckedDir4'); readdir($dir);
        $this->atomFunctionIs('opendir')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->raw('filter{ it.in("CODE").in("CONDITION").any() == false }')
             ->_as('result')
             ->outIs('LEFT')
//             ->savePropertyAs('code', 'resource')
//             ->inIs('LEFT')
             ->nextVariable('resource')
             ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").has("fullnspath", "\\\\is_resource").any() == false }')
             ->raw('filter{ it.in("NOT").any() == false }')
             ->raw('filter{ it.in("ARGUMENT").in("ARGUMENTS").in("RIGHT").in("CODE").in("RIGHT").has("atom", "Comparison").in("CONDITION").any() == false }')
             ->back('result');
//        $this->printQuery();
        $this->prepareQuery();
    }
}

?>
