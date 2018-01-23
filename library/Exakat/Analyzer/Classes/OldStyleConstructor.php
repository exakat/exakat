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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class OldStyleConstructor extends Analyzer {
    public function analyze() {
        $__construct = $this->dictCode->translate(array('__construct'));
        
        // No __construct found
        if (empty($__construct)) {
            $hasNo__construct = 'filter{ true; }';
        } else {
            $hasNo__construct = 'not( where( __.out("MAGICMETHOD").out("NAME").filter{ it.get().value("code") in ***} ) )';
        }


        // No mentionned namespaces
        $this->atomIs('Class')
             ->regexIs('fullnspath', '^\\\\\\\\[^\\\\\\\\]+\$')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->raw($hasNo__construct, $__construct)
             ->outIs('METHOD')
             ->atomIs('Method')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
