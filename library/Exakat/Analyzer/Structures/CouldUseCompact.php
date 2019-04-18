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

class CouldUseCompact extends Analyzer {
    public function analyze() {
        // $a = array('a' => $a, 'b' => $b);
        $this->atomIs('Arrayliteral')
            // Only keep Keyvalue and void
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('ARGUMENT')
                             ->atomIsNot(array('Keyvalue', 'Void'))
                     )
             )
            // At least one Keyvalue
             ->filter(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->atomIs('Keyvalue')
             )
            // Only keep String as name
            ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('ARGUMENT')
                             ->atomIs('Keyvalue')
                             ->outIs('INDEX')
                             ->not(
                                $this->side()
                                     ->filter(
                                        $this->side()
                                             ->atomIs(array('String', 'Identifier', 'Nsname', 'Concatenation'))
                                             ->has('noDelimiter')
                                     )
                             )
                     )
            )
            ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('ARGUMENT')
                             ->atomIs('Keyvalue')
                             ->outIs('VALUE')
                             ->not(
                                $this->side()
                                     ->filter(
                                        $this->side()
                                             ->atomIs('Variable')
                                     )
                             )
                     )
            )
            
            // Only string = variable name
            ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('ARGUMENT')
                             ->atomIs('Keyvalue')
                             ->filter(
                                $this->side()
                                     ->outIs('INDEX')
                                     ->atomIs(array('String', 'Identifier', 'Nsname', 'Concatenation'))
                                     ->raw('sideEffect{ name = "\\$" + it.get().value("noDelimiter"); }')
                                     ->inIs('INDEX')
                                     ->outIs('VALUE')
                                     ->atomIs('Variable')
                                     ->raw('filter{it.get().value("fullcode") != name}')
                             )
                     )
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
