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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class CyclicReferences extends Analyzer {
    public function analyze(): void {
        // Detects short cycles of reference : $a->p->method($a) ($a -> p -> $a)
        // TODO : Detects longer cycles of reference : $a->p->method($a) ($a -> p -> $a)
        // TODO : Exclude cases where the value is not stored in the final object (no reference)

        // $this->p->method($this)
        $this->atomIs('This')
             ->inIs('OBJECT')
             ->atomIs('Member')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->as('result')
             ->outIs('METHOD')
             ->outIs('ARGUMENT')
             ->atomIs('This')
             ->back('result');
        $this->prepareQuery();

        // $a->p->method($a)
        $this->atomIs('Variableobject')
             ->savePropertyAs('code', 'name')
             ->inIs('OBJECT')
             ->atomIs('Member')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->as('result')
             ->outIs('METHOD')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'name')
             ->back('result');
        $this->prepareQuery();
    }
}

?>
