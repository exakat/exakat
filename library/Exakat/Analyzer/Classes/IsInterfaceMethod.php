<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
    public function analyze() {
        // interface extended in the local class
        $this->atomIs(self::$FUNCTIONS_METHOD)
             ->outIs('OVERWRITE')
             ->inIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs('Interface')
             ->back('first');
        $this->prepareQuery();

        // PHP or extension defined interface
        $interfaces = $this->loadJson('php_interfaces_methods.json', 'interface');
        
        foreach($interfaces as $interface => $methods) {
            if (empty($methods)) {
                // may be the case for Traversable : interface without methods
                continue;
            }
            $methodNames = array_column($methods, 'name');
            
            // interface locally implemented
            $this->atomIs(self::$FUNCTIONS_METHOD)
                 ->outIs('NAME')
                 ->codeIs($methodNames, self::TRANSLATE, self::CASE_INSENSITIVE)
                 ->inIs('NAME')
                 ->inIs('METHOD')
                 ->atomIs('Class')
                 ->goToAllImplements(self::INCLUDE_SELF)
                 ->outIs('IMPLEMENTS')
                 ->fullnspathIs($interface)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
