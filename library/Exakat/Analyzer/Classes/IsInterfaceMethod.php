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

class IsInterfaceMethod extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenMethods',
                    );
    }

    public function analyze(): void {
        // interface extended in the local class
        $this->atomIs('Interface')
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->inIs('OVERWRITE')
             ->atomIs(self::FUNCTIONS_METHOD)
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // PHP or extension defined interface
        $interfaces = $this->loadJson('php_interfaces_methods.json', 'interface');

        $hash = array();
        foreach($interfaces as $interface => $methods) {
            if (empty($methods)) {
                // may be the case for Traversable : interface without methods
                continue;
            }

            foreach($methods as $method) {
                array_collect_by($hash, $method->name, $interface);
            }
        }

        // interface locally implemented
        $this->atomIs(self::FUNCTIONS_METHOD)
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->codeIs(array_keys($hash), self::TRANSLATE, self::CASE_INSENSITIVE)
             ->savePropertyAs('fullcode', 'name')
             ->raw('sideEffect{ name = name.toLowerCase(); }')
             ->inIs('NAME')
             ->inIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs('Class')
             ->goToAllImplements(self::INCLUDE_SELF)
             ->outIs('IMPLEMENTS')
             ->isHash('fullnspath', $hash, 'name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
