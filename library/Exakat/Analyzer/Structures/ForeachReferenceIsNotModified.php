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

class ForeachReferenceIsNotModified extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/MakeFunctioncallWithReference',
                    );
    }

    public function analyze(): void {
        // case of a variable
        // foreach($a as &$b) { $c += $b; } // $b is not modified
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('RIGHT')
             ->is('reference', true)
             ->atomIs('Variable')
             ->savePropertyAs('code', 'name')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->not(
                $this->side()
                     ->atomInsideNoDefinition(self::VARIABLES_USER)
                     ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
                     ->inIsIE(array('VARIABLE', 'OBJECT'))
                     ->is('isModified', true)
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
