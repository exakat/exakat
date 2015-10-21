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


namespace Analyzer\Php;

use Analyzer;

class ListWithAppends extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_LIST')

             // more than one Arrayappend, for initial filtering
             ->filter('it.out("ARGUMENTS").out("ARGUMENT").has("atom", "Arrayappend").count() > 1')

             // several appends to the same array
             ->filter('it.out("ARGUMENTS").out("ARGUMENT").has("atom", "Arrayappend").groupCount{it.out("VARIABLE").next().code}{it.b + 1}.cap.next().findAll{it.value > 1}.any()')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
