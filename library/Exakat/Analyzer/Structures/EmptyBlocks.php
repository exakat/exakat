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

class EmptyBlocks extends Analyzer {
    public function analyze(): void {
        // Block with only one empty expression
        // Block with only empty expressions
        $this->atomIs(array('For', 'While', 'Foreach', 'Dowhile', 'Declare', 'Namespace', 'Declare', 'Switch', 'Match'))
             ->not(
                $this->side()
                     ->outIs('DECLARE')
                     ->outIs('NAME')
                     ->codeIs('strict_types')
             )
             ->outIs(array('CASES', 'BLOCK'))
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('EXPRESSION')
                             ->atomIsNot('Void')
                     )
             )
             ->back('first');
        $this->prepareQuery();

        // Empty block on ifthen
        $this->atomIs('Ifthen')
             ->outIs(array('THEN', 'ELSE'))
             ->atomIs('Sequence')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('EXPRESSION')
                             ->atomIsNot('Void')
                     )
             )
             ->back('first')
             ->inIsIE('ELSE');
        $this->prepareQuery();

    }
}

?>
