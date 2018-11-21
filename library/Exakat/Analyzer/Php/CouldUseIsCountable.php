<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class CouldUseIsCountable extends Analyzer {
    protected $phpVersion = '7.3+';

    public function analyze() {
        // is_array($x) or $x instanceof \Countable
        $this->atomIs('Logical')
             ->tokenIs(array('T_LOGICAL_OR', 'T_LOGICAL_XOR'))

             ->outIs(array('LEFT', 'RIGHT'))
             ->functioncallIs('\\is_array')
             ->inIs()

             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Instanceof')
             ->outIs('CLASS')
             ->fullnspathIs('\\Countable')

             ->back('first');
        $this->prepareQuery();

        // is_array($x) or $x instanceof \Countable
        $this->atomIs('Logical')
             ->tokenIs('T_LOGICAL_AND')

             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Not')
             ->outIs('NOT')
             ->functioncallIs('\\is_array')
             ->back('first')

             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Not')
             ->outIs('NOT')
             ->atomIs('Instanceof')
             ->outIs('CLASS')
             ->fullnspathIs('\\Countable')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
