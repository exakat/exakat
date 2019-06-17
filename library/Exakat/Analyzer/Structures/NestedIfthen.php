<?php
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

class NestedIfthen extends Analyzer {
    protected $nestedIfthen = 3;

    public function analyze() {
        $this->nestedIfthen = abs((int) $this->nestedIfthen);
        $this->nestedIfthen = $this->nestedIfthen === 0 ? 1 : $this->nestedIfthen;
        
        // 3 level of ifthen (2 is OK)
        $this->atomIs('Ifthen')
             ->tokenIsNot('T_ELSEIF');
        
        // Skip the first one
        for ($i = 1; $i < $this->nestedIfthen; ++$i) {
            $this->outIs(array('THEN', 'ELSE'))
                 ->atomInsideNoDefinition('Ifthen');
        }

        $this->back('first');
        $this->prepareQuery();
    }
}

?>
