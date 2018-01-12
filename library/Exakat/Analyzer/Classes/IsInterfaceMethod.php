<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
        $this->atomIs('Method')
             ->saveMethodName('name')
             ->goToClass()
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->outIs('METHOD')
             ->atomIs('Method')
             ->saveMethodName('name2')
             ->filter('name == name2')
             ->back('first');
        $this->prepareQuery();

        // interface extended in the parent interface
        $this->atomIs('Method')
             ->saveMethodName('name')
             ->goToClass()
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->outIs('EXTENDS')
             ->interfaceDefinition()
             ->outIs('METHOD')
             ->atomIs('Method')
             ->saveMethodName('name2')
             ->filter('name == name2')
             ->back('first');
        $this->prepareQuery();
        
        // interface defined in the parents
        $this->atomIs('Method')
             ->saveMethodName('name')
             ->goToClass()
             ->goToAllParents()
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->outIs('METHOD')
             ->atomIs('Method')
             ->saveMethodName('name2')
             ->filter('name == name2')
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
            $this->atomIs('Method')
                 ->outIs('NAME')
                 ->codeIs($methods, self::TRANSLATE, self::CASE_INSENSITIVE)
                 ->inIs('NAME')
                 ->inIs('METHOD')
                 ->atomIs('Class')
                 ->outIs('IMPLEMENTS')
                 ->fullnspathIs('\\'.$interface)
                 ->back('first');
            $this->prepareQuery();

            // interface implemented by parents
            $this->atomIs('Method')
                 ->outIs('NAME')
                 ->codeIs($methods, self::TRANSLATE, self::CASE_INSENSITIVE)
                 ->inIs('NAME')
                 ->inIs('METHOD')
                 ->atomIs('Class')
                 ->goToAllParents()
                 ->outIs('IMPLEMENTS')
                 ->fullnspathIs('\\'.$interface)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
