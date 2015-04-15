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


namespace Analyzer\Interfaces;

use Analyzer;

class UselessInterfaces extends Analyzer\Analyzer {

    public function analyze() {
        // interface not used in a instanceof nor a Typehint
        $this->atomIs('Interface')
             ->savePropertyAs('fullnspath', 'interfacedns')
             ->raw('filter{ g.idx("atoms")[["atom":"Class"]].out("IMPLEMENTS").has("fullnspath", interfacedns).any() ||
                            g.idx("atoms")[["atom":"Interface"]].out("EXTENDS").has("fullnspath", interfacedns).any()}')
             ->raw('filter{ g.idx("atoms")[["atom":"Instanceof"]].out("CLASS").has("fullnspath", interfacedns).any() == false}')
             ->raw('filter{ g.idx("atoms")[["atom":"Typehint"]].out("CLASS").has("fullnspath", interfacedns).any() == false}');
        $this->prepareQuery();
    }
}

?>
