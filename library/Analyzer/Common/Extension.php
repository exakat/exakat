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


namespace Analyzer\Common;

use Analyzer;

class Extension extends Analyzer\Analyzer {
    protected $source = '';
    
    public function dependsOn() {
        return array('Classes/ClassUsage',
                     'Interfaces/InterfaceUsage',
                     'Traits/TraitUsage',
                     'Constants/ConstantUsage',
                     'Namespaces/NamespaceUsage'
                     );
    }
    
    public function analyze() {
        $functions  = array();
        $constants  = array();
        $classes    = array();
        $interfaces = array();
        $traits     = array();
        $namespaces = array();

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
        } else {
            echo "Cannot process the '", $this->source, "' file. It has to be .ini format.\n";
            return true;
        }
        
        if (!empty($functions)) {
            $functions = $this->makeFullNsPath($functions);
            $this->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->fullnspath($functions);
            $this->prepareQuery();
        }
        
        if (!empty($constants)) {
            $this->atomIs('Identifier')
                 ->analyzerIs('Constants/ConstantUsage')
                 ->fullnspath($this->makeFullNsPath($constants));
            $this->prepareQuery();
        }

        if (!empty($classes)) {
            $classes = $this->makeFullNsPath($classes);

            $this->atomIs('New')
                 ->outIs('NEW')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->atomIsNot(array('Variable', 'Array', 'Property', 'Staticproperty', 'Methodcall', 'Staticmethodcall'))
                 ->fullnspath($classes);
            $this->prepareQuery();

            $this->atomIs('Staticconstant')
                 ->outIs('CLASS')
                 ->fullnspath($classes);
            $this->prepareQuery();

            $this->atomIs('Staticmethodcall')
                 ->outIs('CLASS')
                 ->fullnspath($classes);
            $this->prepareQuery();

            $this->atomIs('Staticproperty')
                 ->outIs('CLASS')
                 ->fullnspath($classes);
            $this->prepareQuery();

            $this->atomIs('Typehint')
                 ->outIs('CLASS')
                 ->fullnspath($classes);
            $this->prepareQuery();

            $this->atomIs('Catch')
                 ->outIs('CLASS')
                 ->fullnspath($classes);
            $this->prepareQuery();

            $this->atomIs('Instanceof')
                 ->outIs('CLASS')
                 ->fullnspath($classes);
            $this->prepareQuery();
        }

        if (!empty($interfaces)) {
            $this->analyzerIs('Interfaces/InterfaceUsage')
                 ->code($interfaces);
            $this->prepareQuery();

            $interfaces = $this->makeFullNsPath($interfaces);
            $this->analyzerIs('Interfaces/InterfaceUsage')
                 ->fullcode($interfaces);
            $this->prepareQuery();
        }

        if (!empty($traits)) {
            $this->analyzerIs('Traits/TraitUsage')
                 ->code($traits);
            $this->prepareQuery();

            $traits = $this->makeFullNsPath($traits);
            $this->analyzerIs('Traits/TraitUsage')
                 ->fullcode($traits);
            $this->prepareQuery();
        }

        if (!empty($namespaces)) {
            $this->analyzerIs('Namespaces/NamespaceUsage')
                 ->code($namespaces);
            $this->prepareQuery();
            
            // Can a namespace be used in a nsname (as prefix) ? 
        }
    }
}

?>
