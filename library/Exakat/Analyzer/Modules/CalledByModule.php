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

namespace Exakat\Analyzer\Modules;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Dictionary;

class CalledByModule extends Analyzer {
    protected $data = array();

    public function analyze() {
        $calledBy = $this->config->dev->loadJson('called_by.json');
        
        // Merging ALL values of all versions.
        if (empty($calledBy)) {
//            print "Error in the JSON file \n";
            return;
        }
        $calledBy = array_merge_recursive(...array_values($calledBy));

        $classes               = array();
        $methods               = array();
        $methods_regex         = array();
        $static_methods        = array();
        $static_methods_regex  = array();
        $classConstants        = array();
        if (isset($calledBy['classes'])) {
            foreach($calledBy['classes'] as $class => $what) {
                // Classes
                if (isset($what['classes'])) {
                    $classes[] = $class;
                }
    
                // No properties : it makes no sense
                // Methods (No handling of visibility)
                if (isset($what['methods'])) {
                    foreach($what['methods'] as $name) {
                        if ($name[0] === '/') {
                            array_collect_by($methods_regex, makeFullnspath($class), trim($name, '/'));
                        } else {
                            array_collect_by($methods, makeFullnspath($class), mb_strtolower($name));
                        }
                    }
                }

                // Static Methods (No handling of visibility)
                if (isset($what['staticmethods'])) {
                    foreach($what['staticmethods'] as $name) {
                        if ($name[0] === '/') {
                            array_collect_by($static_methods_regex, makeFullnspath($class), mb_strtolower(trim($name, '/')));
                        } else {
                            array_collect_by($static_methods, makeFullnspath($class),  mb_strtolower(mb_strtolower($name)));
                        }
                    }
                }

                // Constants (No handling of visibility)
                if (isset($what['constants'])) {
                    foreach($what['constants'] as $name) {
                        array_collect_by($classConstants, makeFullnspath($class), $name);
                    }
                }
            }

            $this->processClasses($classes);

            $this->processClassConstants($classConstants);

            $this->processMethods($methods);
            $this->processMethodsRegex($methods_regex);

            $this->processStaticMethods($static_methods);
            $this->processStaticMethodsRegex($static_methods_regex);

// No usage for those
//            $this->processTraits($classes);
//            $this->processInterfaces($classes);
//            $this->processProperties($classes);
        }

        $this->processFunctions($calledBy['functions']);
        $this->processConstants($calledBy['constants']);
// No usage for variables
//        $this->processVariables($calledBy['variables']);
    }

    private function processFunctions($functions) {
        if (empty($functions)) {
            return;
        }

        $this->atomIs('Function')
             ->fullnspathIs($functions);
        $this->prepareQuery();
    }

    private function processConstants($constants) {
        if (empty($constants)) {
            return;
        }

        $this->atomIs('Defineconstant')
             ->outIs('NAME')
             ->fullnspathIs($constants);
        $this->prepareQuery();

        $this->atomIs('Const')
             ->outIs('CONST')
             ->outIs('NAME')
             ->fullnspathIs($constants)
             ->inIs('NAME');
        $this->prepareQuery();
    }

    private function processClasses($classes) {
        if (empty($classes)) {
            return;
        }

        $this->atomIs('Class')
             ->outIs('EXTENDS')
             ->atomIs(array('Identifier', 'Nsname'))
             ->is('fullnspath', $classes)
             ->back('first');
        $this->prepareQuery();
    }

    private function processClassConstants($constants) {
        if (empty($constants)) {
            return;
        }

        $this->atomIs(self::$CLASSES_ALL)
             ->fullnspathIs(array_keys($constants))
             ->savePropertyAs('fullnspath', 'fqn')
             ->outIs('CONST')
             ->outIs('CONST')
             ->outIs('NAME')
             ->isHash('fullcode', $constants, 'fqn')
             ->inIs('NAME');
        $this->prepareQuery();
    }

    private function processMethods($methods) {
        foreach($methods as &$method) {
            $method = $this->dictCode->translate(array_unique($method), Dictionary::CASE_INSENSITIVE);
        }
        unset($method);
        $methods = array_filter($methods);

        if (empty($methods)) {
            return;
        }

        // Check that the class extends one of the mentionned called class
        $this->atomIs('Class')
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('EXTENDS')
             ->fullnspathIs(array_keys($methods))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('METHOD')
             ->_as('results')
             ->outIs('NAME')
             ->isHash('lccode', $methods, 'fnp')
             ->back('results');
        $this->prepareQuery();

        // Check that the class implements one of the mentionned called interface
        $this->atomIs(self::$CLASSES_ALL)
             ->goToAllImplements(self::INCLUDE_SELF)
             ->outIs('IMPLEMENTS')
             ->fullnspathIs(array_keys($methods))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs(self::$CLASS_METHODS)
             ->is('static', true)
             ->_as('results')
             ->outIs('NAME')
             ->isHash('lccode', $methods, 'fnp')
             ->back('results');
        $this->prepareQuery();    }

    private function processMethodsRegex($methods_regex) {
        if (empty($methods_regex)) {
            return;
        }
        
        $this->atomIs('Class')
             ->outIs('EXTENDS')
             ->fullnspathIs(array_keys($methods_regex))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('METHOD')
             ->_as('results')
             ->outIs('NAME')
             ->raw(<<<'GREMLIN'
has("fullcode").filter{ (it.get().value("fullcode") =~ ***[fnp] ).getCount() != 0  }
GREMLIN
, array($methods_regex) )
             ->back('results');
        $this->prepareQuery();
    }

    private function processStaticMethods($methods) {
        foreach($methods as &$method) {
            $method = $this->dictCode->translate(array_unique($method), Dictionary::CASE_INSENSITIVE);
            print_r($method);
        }
        unset($method);
        $methods = array_filter($methods);

        if (empty($methods)) {
            print 'No mtehods';
            return;
        }

        // Check that the class extends one of the mentionned called class
        $this->atomIs(self::$CLASSES_ALL)
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('EXTENDS')
             ->fullnspathIs(array_keys($methods))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs(self::$CLASS_METHODS)
             ->is('static', true)
             ->_as('results')
             ->outIs('NAME')
             ->isHash('lccode', $methods, 'fnp')
             ->back('results');
        $this->prepareQuery();

        // Check that the class implements one of the mentionned called interface
        $this->atomIs(self::$CLASSES_ALL)
             ->goToAllImplements(self::INCLUDE_SELF)
             ->outIs('IMPLEMENTS')
             ->fullnspathIs(array_keys($methods))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs(self::$CLASS_METHODS)
             ->is('static', true)
             ->_as('results')
             ->outIs('NAME')
             ->isHash('lccode', $methods, 'fnp')
             ->back('results');
        $this->prepareQuery();
        
        //what can we do with Trait? 
    }

    private function processStaticMethodsRegex($methods_regex) {
        if (empty($methods_regex)) {
            return;
        }
        
        $this->atomIs('Class')
             ->outIs('EXTENDS')
             ->fullnspathIs(array_keys($methods_regex))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs(self::$FUNCTIONS_METHOD)
             ->is('static', true)
             ->_as('results')
             ->outIs('NAME')
             ->raw(<<<'GREMLIN'
has("fullcode").filter{ (it.get().value("fullcode") =~ ***[fnp] ).getCount() != 0  }
GREMLIN
, array($methods_regex) )
             ->back('results');
        $this->prepareQuery();
    }
}

?>
