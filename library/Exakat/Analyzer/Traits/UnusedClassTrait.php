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

class UnusedClassTrait extends Analyzer {
    /* PHP version restrictions
    protected $phpVersion = '7.4-';
    */

    public function dependsOn(): array {
        return array('Complete/MakeClassMethodDefinition',
                    );
    }

    public function analyze() {
        // trait t {}
        // class x { use T; /* No use of T */ }
        $this->atomIs('Class')
             ->as('c')
             ->outIs('USE')
             ->outIs('USE')
             ->not(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs(self::CLASS_METHODS)
                     ->as('r')

                     ->outIs('DEFINITION')
                     ->atomIs(array('Methodcall'))
                     ->goToClass('Class')
                     ->atomIs('Class')
                     ->raw('where( eq("c") )')
                )
                ->back('first');
        $this->prepareQuery();
    }
}

?>
