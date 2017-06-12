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


namespace Exakat\Analyzer\Common;

use Exakat\Analyzer\Analyzer;

class Extension extends Analyzer {
    protected $source = '';
    
    public function dependsOn() {
        return array('Classes/ClassUsage',
                     'Interfaces/InterfaceUsage',
                     'Traits/TraitUsage',
                     'Constants/ConstantUsage',
                     'Namespaces/NamespaceUsage',
                     'Php/DirectivesUsage',
                     );
    }
    
    
    public function analyze() {
        if (substr($this->source, -4) == '.ini') {
            $ini = $this->loadIni($this->source);
        } else {
            return true;
        }
        
        if (!empty($ini['functions'])) {
            $functions = $this->makeFullNsPath($ini['functions']);
            $this->atomFunctionIs($functions);
            $this->prepareQuery();
        }
        
        if (!empty($ini['constants'])) {
            $this->atomIs('Identifier')
                 ->analyzerIs('Constants/ConstantUsage')
                 ->fullnspathIs($this->makeFullNsPath($ini['constants']));
            $this->prepareQuery();
        }

        if (!empty($ini['classes'])) {
            $classes = $this->makeFullNsPath($ini['classes']);

            $this->atomIs('New')
                 ->outIs('NEW')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->raw('has("fullnspath")')
                 ->fullnspathIs($classes);
            $this->prepareQuery();

            $this->atomIs(array('Staticconstant', 'Staticmethodcall', 'Staticproperty'))
                 ->outIs('CLASS')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->fullnspathIs($classes);
            $this->prepareQuery();

            $this->atomIs('Function')
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->outIs('TYPEHINT')
                 ->fullnspathIs($classes);
            $this->prepareQuery();

            $this->atomIs('Catch')
                 ->outIs('CLASS')
                 ->fullnspathIs($classes);
            $this->prepareQuery();

            $this->atomIs('Instanceof')
                 ->outIs('CLASS')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->atomIsNot(array('Array', 'Boolean', 'Null'))
                 ->fullnspathIs($classes);
            $this->prepareQuery();
        }

        if (!empty($ini['interfaces'])) {
            $interfaces = $this->makeFullNsPath($ini['interfaces']);
            $this->analyzerIs('Interfaces/InterfaceUsage')
                 ->fullnspathIs($interfaces);
            $this->prepareQuery();
        }

        if (!empty($ini['traits'])) {
            $this->analyzerIs('Traits/TraitUsage')
                 ->codeIs($ini['traits']);
            $this->prepareQuery();

            $traits = $this->makeFullNsPath($ini['traits']);
            $this->analyzerIs('Traits/TraitUsage')
                 ->outIs('USE')
                 ->fullnspathIs($traits);
            $this->prepareQuery();
        }

        if (!empty($ini['namespaces'])) {
            $namespaces = $this->makeFullNsPath($ini['namespaces']);
            $this->analyzerIs('Namespaces/NamespaceUsage')
                 ->fullnspathIs($namespaces)
                 ->back('first');
            $this->prepareQuery();
            
            // Can a namespace be used in a nsname (as prefix) ?
        }

        if (!empty($ini['directives'])) {
            $this->analyzerIs('Php/DirectivesUsage')
                 ->outIs('ARGUMENTS')
                 ->outWithRank("ARGUMENT", 0)
                 ->noDelimiterIs($ini['directives']);
            $this->prepareQuery();
            
            // Can a namespace be used in a nsname (as prefix) ?
        }
    }
}

?>
