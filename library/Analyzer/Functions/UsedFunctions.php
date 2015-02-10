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


namespace Analyzer\Functions;

use Analyzer;

class UsedFunctions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\MarkCallable');
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->raw('filter{ it.in("ELEMENT").in("BLOCK").has("atom", "Class").any() == false}')
             ->raw('filter{it.out("NAME").next().code != ""}')
             ->outIs('NAME')
             ->raw("filter{ g.idx('atoms')[['atom':'Functioncall']].has('fullnspath', it.fullnspath).any() }");
        $this->prepareQuery();

        $this->atomIs('Function')
             ->outIs('NAME')
             ->raw('filter{ f = it; g.idx("atoms")[["atom":"String"]].hasNot("fullnspath", null).filter{it.fullnspath == f.fullnspath; }.any()}');
        $this->prepareQuery();
    }
}

?>
