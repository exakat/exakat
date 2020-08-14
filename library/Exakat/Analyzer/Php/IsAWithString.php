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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class IsAWithString extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/CreateDefaultValues',
                     'Complete/PropagateCalls',
                    );
    }

    public function analyze(): void {
        // is_a('a', 'b');
        $this->atomFunctionIs(array('\\is_a',
                                    '\\is_subclass_of',
                                   ))
             ->noChildWithRank('ARGUMENT', 2)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String', self::WITH_CONSTANTS)
             ->back('first');
        $this->prepareQuery();

        // is_a('a', 'b', false);
        $this->atomFunctionIs(array('\\is_a',
                                    '\\is_subclass_of',
                                   ))
             ->outWithRank('ARGUMENT', 2)
             ->is('boolean', false)
             ->back('first')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String', self::WITH_CONSTANTS)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
