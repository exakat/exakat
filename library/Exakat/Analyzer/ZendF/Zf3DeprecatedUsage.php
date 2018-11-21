<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\ZendF;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\ZendF3;

class Zf3DeprecatedUsage extends Analyzer {
    public function dependsOn() {
        return array('ZendF/ZendClasses',
                     'ZendF/ZendInterfaces',
                     'ZendF/ZendTrait',
                    );
    }
    
    public function analyze() {
        $zend3 = new ZendF3($this->config->dir_root.'/data', $this->config);

        $list = $zend3->getDeprecated();

        // Methods
        $deprecated = call_user_func_array('array_merge', array_values($list['function']));
        $methods = array_column($deprecated, 'name');
        if (!empty($methods)) {
            $this->atomIs('Methodcall')
                 ->outIs('METHOD')
                 ->codeIs($methods, self::CASE_SENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }
        
        // Properties
        $deprecated = call_user_func_array('array_merge', array_values($list['property']));
        $properties = array_column($deprecated, 'name');
        if (!empty($methods)) {
            $this->atomIs('Member')
                 ->outIs('MEMBER')
                 ->codeIs($properties, self::CASE_SENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }

        // Constants
        $deprecated = call_user_func_array('array_merge', array_values($list['const']));
        $constants = array_column($deprecated, 'name');
        if (!empty($methods)) {
            $this->atomIs('Staticconstant')
                 ->outIs('CONSTANT')
                 ->codeIs($constants)
                 ->back('first');
            $this->prepareQuery();
        }

        // Class
        $deprecated = call_user_func_array('array_merge', array_values($list['class']));
        $classes = array();
        foreach($deprecated as $d) {
            $classes[$d['namespace'].'\\'.$d['name']] = 1;
        }
        $classes = makeFullnspath(array_keys($classes));
        if (!empty($classes)) {
            $this->analyzerIs('ZendF/ZendClasses')
                 ->fullnspathIs($classes)
                 ->back('first');
            $this->prepareQuery();
        }

        // Interface
        $deprecated = call_user_func_array('array_merge', array_values($list['interface']));
        $interfaces = array();
        foreach($deprecated as $d) {
            $interfaces[$d['namespace'].'\\'.$d['name']] = 1;
        }
        $interfaces = makeFullnspath(array_keys($interfaces));
        if (!empty($interfaces)) {
            $this->analyzerIs('ZendF/ZendInterfaces')
                 ->fullnspathIs($interfaces)
                 ->back('first');
            $this->prepareQuery();
        }

        // Trait
        $deprecated = call_user_func_array('array_merge', array_values($list['trait']));
        $traits = array();
        foreach($deprecated as $d) {
            $traits[$d['namespace'].'\\'.$d['name']] = 1;
        }
        $traits = makeFullnspath(array_keys($traits));
        if (!empty($interfaces)) {
            $this->analyzerIs('ZendF/ZendTrait')
                 ->fullnspathIs($traits)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
