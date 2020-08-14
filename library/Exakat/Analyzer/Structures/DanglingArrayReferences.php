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

class DanglingArrayReferences extends Analyzer {
    public function analyze(): void {
        //foreach($a as &$b) {}
        // No following unset()
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->is('reference', true)
             ->savePropertyAs('code', 'array')
             ->back('first')
             ->nextSibling()

            // is it unset($x); ?
            ->not(
                $this->side()
                     ->atomIs('Unset')
                     ->outIs('ARGUMENT')
                     ->samePropertyAs('code', 'array')
            )

            // is is (unset) $x;?
            ->not(
                $this->side()
                     ->atomIs('Cast')
                     ->tokenIs('T_UNSET_CAST')
                     ->outIs('CAST')
                     ->samePropertyAs('code', 'array')
            )

            ->back('first');
        $this->prepareQuery();
    }
}

?>
