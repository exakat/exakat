<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Common;

use Analyzer;

class Extension extends Analyzer\Analyzer {
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
        $functions  = array();
        $constants  = array();
        $classes    = array();
        $interfaces = array();
        $traits     = array();
        $namespaces = array();
        $directives = array();

        if (substr($this->source, -4) == '.ini') {
            $ini = $this->loadIni($this->source);
            extract($ini);
            
            if (count($functions) == 1 && empty($functions[0])) {
                $functions = array();
            }

            if (count($constants) == 1 && empty($constants[0])) {
                $constants = array();
            }

            if (count($classes) == 1 && empty($classes[0])) {
                $classes = array();
            }

            if (count($interfaces) == 1 && empty($interfaces[0])) {
                $interfaces = array();
            }

            if (count($traits) == 1 && empty($traits[0])) {
                $traits = array();
            }

            if (count($namespaces) == 1 && empty($namespaces[0])) {
                $namespaces = array();
            }

            if (count($directives) == 1 && empty($directives[0])) {
                $directives = array();
            }
        } else {
            echo "Cannot process the '", $this->source, "' file. It has to be .ini format.\n";
            return true;
        }
        
        if (!empty($functions)) {
            $functions = $this->makeFullNsPath($functions);
            $this->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->fullnspathIs($functions);
            $this->prepareQuery();
        }
        
        if (!empty($constants)) {
            $this->atomIs('Identifier')
                 ->analyzerIs('Constants/ConstantUsage')
                 ->fullnspathIs($this->makeFullNsPath($constants));
            $this->prepareQuery();
        }

        if (!empty($classes)) {
            $classes = $this->makeFullNsPath($classes);

            $this->atomIs('New')
                 ->outIs('NEW')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->atomIsNot(array('Variable', 'Array', 'Property', 'Staticproperty', 'Methodcall', 'Staticmethodcall'))
                 ->fullnspathIs($classes);
            $this->prepareQuery();

            $this->atomIs('Staticconstant')
                 ->outIs('CLASS')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->fullnspathIs($classes);
            $this->prepareQuery();

            $this->atomIs('Staticmethodcall')
                 ->outIs('CLASS')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->fullnspathIs($classes);
            $this->prepareQuery();

            $this->atomIs('Staticproperty')
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
                 ->fullnspathIs($classes);
            $this->prepareQuery();
        }

        if (!empty($interfaces)) {
            $interfaces = $this->makeFullNsPath($interfaces);
            $this->analyzerIs('Interfaces/InterfaceUsage')
                 ->fullnspathIs($interfaces);
            $this->prepareQuery();
        }

        if (!empty($traits)) {
            $this->analyzerIs('Traits/TraitUsage')
                 ->codeIs($traits);
            $this->prepareQuery();

            $traits = $this->makeFullNsPath($traits);
            $this->analyzerIs('Traits/TraitUsage')
                 ->fullcode($traits);
            $this->prepareQuery();
        }

        if (!empty($namespaces)) {
            $namespaces = $this->makeFullNsPath($namespaces);
            $this->analyzerIs('Namespaces/NamespaceUsage')
                 ->outIs('NAME')
                 ->fullnspathIs($namespaces)
                 ->back('first');
            $this->prepareQuery();
            
            // Can a namespace be used in a nsname (as prefix) ? 
        }

        if (!empty($directives)) {
            $namespaces = $this->makeFullNsPath($namespaces);
            $this->analyzerIs('Php/DirectivesUsage')
                 ->outIs('ARGUMENTS')
                 ->outWithRank("ARGUMENT", 0)
                 ->noDelimiterIs($directives);
            $this->prepareQuery();
            
            // Can a namespace be used in a nsname (as prefix) ? 
        }
    }
}

?>
