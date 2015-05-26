<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Classes;

use Analyzer;

class IsInterfaceMethod extends Analyzer\Analyzer {
    public function analyze() {
        // interface defined in the local class
        $this->atomIs('Function')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // interface defined in the parents
        $this->atomIs('Function')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->goToAllParents()
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // PHP or extension defined interface
        $interfaces = $this->loadIni('php_interfaces_methods.ini', 'interface');
        
        foreach($interfaces as $interface => $methods) {
            if (empty($methods)) {
                // may be the case for Traversable : interface without methods
                continue;
            }
            $methods = explode(',', $methods);
            
            // interface locally implemented
            $this->atomIs('Function')
                 ->outIs('NAME')
                 ->code($methods)
                 ->inIs('NAME')
                 ->inIs('ELEMENT')
                 ->inIs('BLOCK')
                 ->atomIs('Class')
                 ->outIs('IMPLEMENTS')
                 ->fullnspath('\\'.$interface)
                 ->back('first');
            $this->prepareQuery();

            // interface implemented by parents
            $this->atomIs('Function')
                 ->outIs('NAME')
                 ->code($methods)
                 ->inIs('NAME')
                 ->inIs('ELEMENT')
                 ->inIs('BLOCK')
                 ->atomIs('Class')
                 ->goToAllParents()
                 ->outIs('IMPLEMENTS')
                 ->fullnspath('\\'.$interface)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
