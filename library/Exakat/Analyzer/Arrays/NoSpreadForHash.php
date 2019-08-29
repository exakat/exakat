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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class NoSpreadForHash extends Analyzer {
    public function dependsOn() {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze() {
        // ...['a' => 3]
        $this->atomIs('Arrayliteral', self::WITHOUT_CONSTANTS)
             ->has('variadic', true)
             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->outIs('INDEX')
             ->atomIs(array('String', 'Heredoc', 'Concatenation'), self::WITH_CONSTANTS)
             ->is('intval', 0)
             ->back('first');
        $this->prepareQuery();

        // const A = ['a' => 3]; ...A
        $this->atomIs(self::$STATIC_NAMES, self::WITHOUT_CONSTANTS)
             ->has('variadic', true)
             ->inIs('DEFINITION')
             ->outIs('VALUE')
             ->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->outIs('INDEX')
             ->atomIs(array('String', 'Heredoc', 'Concatenation'), self::WITH_CONSTANTS)
             ->is('intval', 0)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
