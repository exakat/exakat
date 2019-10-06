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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class OnlyVariablePassedByReference extends Analyzer {
    public function dependsOn() {
        return array('Complete/PropagateCalls',
                     'Complete/MakeFunctioncallWithReference',
                    );
    }

    public function analyze() {
        // custom calls
        $this->atomIs(self::$CALLS)
             ->hasIn('DEFINITION')  // No definition, no check
             ->outIsIE('METHOD')
             ->outIs('ARGUMENT')
             ->is('isModified', true)
             ->atomIsNot(self::$CONTAINERS_PHP)
             ->back('first');
        $this->prepareQuery();

        // PHP Functioncalls
        $phpNative = self::$methods->getFunctionsReferenceArgs();
        $phpNative = array_column($phpNative, 'function');
        $phpNative = array_unique($phpNative);
        $phpNative = array_values($phpNative);
        $phpNative = makeFullnspath($phpNative);

        $this->atomIs('Functioncall')
             ->hasNoIn('DEFINITION')  // No definition, no check
             ->fullnspathIs($phpNative)
             ->outIs('ARGUMENT')
             ->is('isModified', true)
             ->atomIsNot(self::$CONTAINERS_PHP)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
