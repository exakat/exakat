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

namespace Exakat\Analyzer\Namespaces;

use Exakat\Analyzer\Analyzer;

class HiddenUse extends Analyzer {
    public function analyze(): void {
        // only for uses with rank of 1 or later
        $this->atomIs(array('Usenamespace', 'Usetrait'))
             ->savePropertyAs('rank', 'ranked')
             ->inIs('EXPRESSION')
             ->filter(
                $this->side()
                     ->outIs('EXPRESSION')
                     ->has('rank')
                     ->isLess('rank', 'ranked')
                     ->atomIsNot(array('Usenamespace', 'Usetrait', 'Declare', 'Include'))
               )
             ->back('first');
        $this->prepareQuery();

        // rank = 0 use are OK
        // inside a class/trait
        $this->atomIs(array('Usenamespace', 'Usetrait'))
             ->savePropertyAs('rank', 'ranked')
             ->inIs('USE')
             ->filter(
                $this->side()
                     ->outIs(array('CONST', 'METHOD', 'PPP'))
                     ->has('rank')
                     ->isLess('rank', 'ranked')
             )
             ->back('first');
        $this->prepareQuery();

    }
}

?>
