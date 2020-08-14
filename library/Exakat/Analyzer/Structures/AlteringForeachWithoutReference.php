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

class AlteringForeachWithoutReference extends Analyzer {
    public function analyze(): void {
        // foreach($a as $k => $v) { $a[$k] += 1;}
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'source')
             ->inIs('SOURCE')

             ->outIs('INDEX')
             ->savePropertyAs('code', 'k')
             ->back('first')

             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Array')
             ->not(
                $this->side()
                     ->inIs('CAST')
                     ->atomIs('Cast')
                     ->tokenIs('T_UNSET_CAST')
             )
             ->not(
                $this->side()
                     ->inIs('ARGUMENT')
                     ->atomIs('Unset')
             )
             ->is('isModified', true)

             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'source')
             ->inIs('VARIABLE')

             ->outIs('INDEX')
             ->samePropertyAs('code', 'k')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
