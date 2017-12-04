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

class PrintfArguments extends Analyzer {
    public function analyze() {
        //The %2$s contains %1$04d monkeys
        //The %02s contains %-'.3d monkeys
    
        // printf(' a %s ', $a1, $a2);
        $this->atomFunctionIs(array('\\printf', '\\sprintf'))
             ->savePropertyAs('count', 'c')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             //(?:[ 0]|\'.{1})?-?\\\d*%(?:\\\.\\\d+)?
             ->filter('d = it.get().value("fullcode").toString().findAll("(?<!%)%(?:\\\d+\\\\\\$)?[+-]?(?:[ 0\']\\\.\\\d+)?(?:\\\d\\\d)?[bcdeEufFgGosxX]"); c - 1 != d.size();')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
