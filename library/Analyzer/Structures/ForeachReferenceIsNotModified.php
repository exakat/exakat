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

class ForeachReferenceIsNotModified extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsModified');
    }
    
    public function analyze() {
        // case of a variable
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->is('reference', true)
             ->savePropertyAs('code', 'value')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->raw('filter{ it.out.loop(1){true}{it.object.atom == "Variable"}.has("code",value).filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").any()}.any() == false}')
             ->back('first');
        $this->prepareQuery();

        // case of a variable
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->is('reference', true)
             ->savePropertyAs('code', 'value')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->raw('filter{ it.out.loop(1){true}{it.object.atom == "Variable"}.has("code",value).filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").any()}.any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
