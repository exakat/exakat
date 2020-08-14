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

class UnusedMethods extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenMethods',
                     'Classes/UsedMethods',
                     'Modules/CalledByModule',
                     'Classes/IsInterfaceMethod',
                     'Classes/DynamicSelfCalls',
                    );
    }

    public function analyze(): void {
        // Magicmethods are supposed to be used automatically
        // Could be checked for __clone, __get, __set...

        // Methods definitions in class
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->isNot('abstract', true)

             // Skip methods that are overwriten. They are default behavior, expected to be replaced
             ->hasNoIn('OVERWRITE')

             // Skip traits
             ->hasClass()
             ->analyzerIsNot(array('Classes/UsedMethods',
                                   'Modules/CalledByModule',
                                ))
            // Checks if it is a PHP interface : it is an interface method, but has no definition as it is implicit
             ->not(
                $this->side()
                     ->analyzerIs('Classes/IsInterfaceMethod')
             )

             // Skip classes that do self dynamical calls : $this->$m()
             ->filter(
                $this->side()
                     ->goToClass()
                     ->analyzerIsNot('Classes/DynamicSelfCalls')
             );
        $this->prepareQuery();

        // Methods definitions in trait
        // Missing OVERWRITE
    }
}

?>
