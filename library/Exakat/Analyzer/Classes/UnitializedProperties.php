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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UnitializedProperties extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenProperties',
                     'Complete/CreateDefaultValues',
                     'Classes/Constructor',
                    );
    }

    public function analyze(): void {
        // Normal Properties (with or without constructor)
        $this->atomIs('Propertydefinition')
             ->not(
                $this->side()
                     ->outIs('DEFAULT')
                     ->goToFunction()
                     ->atomIs('Magicmethod')
                     ->analyzerIs('Classes/Constructor')
             )
             // No explicit default value
             ->outIs('DEFAULT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
