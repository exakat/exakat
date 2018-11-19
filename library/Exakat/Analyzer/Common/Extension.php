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
        if (substr($this->source, -4) !== '.ini') {
            return true;
        }

        $ini = $this->loadIni($this->source);
        
        if (!empty($ini['functions'])) {
            $functions = makeFullNsPath($ini['functions']);
            $this->atomFunctionIs($functions);
            $this->prepareQuery();
        }
        
        if (!empty($ini['constants'])) {
            $this->atomIs(array('Identifier', 'Nsname'))
                 ->analyzerIs('Constants/ConstantUsage')
                 ->fullnspathIs(makeFullNsPath($ini['constants']));
            $this->prepareQuery();
        }

        if (!empty($ini['classes'])) {
            $classes = makeFullNsPath($ini['classes']);
            
            $usedClasses = array_intersect(self::getCalledClasses(), $classes);
            if (!empty($usedClasses)) {
                $usedClasses = array_values($usedClasses);
                $this->atomIs('New')
                     ->outIs('NEW')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();
    
                $this->atomIs(array('Staticconstant', 'Staticmethodcall', 'Staticproperty'))
                     ->outIs('CLASS')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();
    
                $this->atomIs(self::$FUNCTIONS_ALL)
                     ->outIs('ARGUMENT')
                     ->outIs('TYPEHINT')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();

                $this->atomIs(self::$FUNCTIONS_ALL)
                     ->outIs('RETURNTYPE')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();
    
                $this->atomIs('Catch')
                     ->outIs('CLASS')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();
    
                $this->atomIs('Instanceof')
                     ->outIs('CLASS')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();
            }
        }

        if (!empty($ini['interfaces'])) {
            $interfaces = makeFullNsPath($ini['interfaces']);
            
            $usedInterfaces = array_intersect(self::getCalledinterfaces(), $interfaces);

            if (!empty($usedInterfaces)) {
                $usedInterfaces = array_values($usedInterfaces);
                $this->analyzerIs('Interfaces/InterfaceUsage')
                     ->fullnspathIs($usedInterfaces);
                $this->prepareQuery();
            }
        }

        if (!empty($ini['traits'])) {
            $traits = makeFullNsPath($ini['traits']);
            
            $usedTraits = array_intersect(self::getCalledtraits(), $traits);

            if (!empty($usedTraits)) {
                $usedTraits = array_values($usedTraits);
                $this->analyzerIs('Traits/TraitUsage')
                     ->fullnspathIs($usedTraits);
                $this->prepareQuery();
            }
        }

        if (!empty($ini['namespaces'])) {
            $namespaces = makeFullNsPath($ini['namespaces']);
            
            $usedNamespaces = array_intersect(self::getCalledNamespaces(), $namespaces);

            if (!empty($usedNamespaces)) {
                $usedNamespaces = array_values($usedNamespaces);
                $this->analyzerIs('Namespaces/NamespaceUsage')
                     ->fullnspathIs($usedNamespaces);
                $this->prepareQuery();
            }
        }

        if (!empty($ini['directives'])) {
            $usedDirectives = array_intersect(self::getCalledDirectives(), $ini['directives']);

            if (!empty($usedDirectives)) {
                $usedDirectives = array_values($usedDirectives);
                $this->analyzerIs('Php/DirectivesUsage')
                     ->outWithRank("ARGUMENT", 0)
                     ->noDelimiterIs($ini['directives'], self::CASE_SENSITIVE);
                $this->prepareQuery();
            }
        }
    }
}

?>
