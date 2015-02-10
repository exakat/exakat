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


namespace Analyzer\Classes;

use Analyzer;

class OverwrittenConst extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Const')
             ->raw('sideEffect{ result = it;}')
             ->outIs('NAME')
             ->savePropertyAs('code', 'constante')
             ->back('first')
             ->raw('filter{ it.out("EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                            .loop(2){true}{true}
                            .filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Const").out("NAME").has("code", constante).any() }.any()}')
             ->raw('transform{ result;}');
        $this->prepareQuery();
    }
}

?>
