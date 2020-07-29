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

namespace Exakat\Analyzer\Traits;

use Exakat\Analyzer\Analyzer;

class MultipleUsage extends Analyzer {
    public function analyze() : void {
        // trait a { use b, c;}
        // trait b { use c;}
        $this->atomIs('Usetrait')
             ->outIs('USE')
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('USE')
             ->inIs('USE')
             ->as('result')
             ->filter(
                $this->side()
                     ->atomIs(self::CIT)
                     ->goToAllTraits(self::EXCLUDE_SELF)
                     ->outIs('USE')
                     ->outIs('USE')
                     ->samePropertyAs('fullnspath', 'fnp')
             )
             ->back('result');
        $this->prepareQuery();

        // trait a { use b;}
        // trait b { use a;}
        $this->atomIs('Trait')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'fnp')
             ->goToAllTraits(self::INCLUDE_SELF)
             ->outIs('USE')
             ->outIs('USE')
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
