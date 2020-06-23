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

namespace Exakat\Analyzer\Typehints;

use Exakat\Analyzer\Analyzer;

class CouldBeString extends Analyzer {
    public function analyze() {
        // return type
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNED')
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();

        // argument type
        // $arg . ''
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->hasIn('CONCAT')
             ->back('result');
        $this->prepareQuery();

        // is_string
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('ARGUMENT')
             ->functioncallIs('\\is_string')
             ->back('result');
        $this->prepareQuery();

    }
}

?>
