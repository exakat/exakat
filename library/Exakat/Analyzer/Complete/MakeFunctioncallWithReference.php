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

namespace Exakat\Analyzer\Complete;

class MakeFunctioncallWithReference extends Complete {
    public function dependsOn(): array {
        return array('Complete/SetClassMethodRemoteDefinition',
                     'Complete/PropagateCalls',
                    );
    }

    public function analyze(): void {
        // Case of PHP native functions
        $methods = $this->methods->getFunctionsReferenceArgs();
        $functions = array();
        foreach($methods as $method) {
            array_collect_by($functions, $method['position'], makeFullnspath($method['function']));
        }

        foreach($functions as $position => $calls) {
            $this->atomFunctionIs($calls)
                 ->outWithRank('ARGUMENT', $position)
                 ->setProperty('isModified', true);
            $this->prepareQuery();
        }

        // Case of Custom native functions
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->is('reference', true)
             ->goToParameterUsage()
             ->setProperty('isModified', true);
        $this->prepareQuery();
    }
}

?>
