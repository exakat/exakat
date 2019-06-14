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

        $methods = array();
        $classes = array();
        $methods_regex = array();
        foreach($calledBy['classes'] as $class => $what) {
            // Classes
            if (isset($what['classes'])) {
                $classes[] = $class;
            }

            // Methods
            if (isset($what['methods'])) {
                foreach($what['methods'] as $name) {
                    if ($name[0] === '/') {
                        array_collect_by($methods_regex, $class, trim($name, '/'));
                    } else {
                        array_collect_by($methods, $class, mb_strtolower($name));
                    }
                }
            }
        }

        $this->processClasses($classes);

        $this->processMethods($methods);
        $this->processMethodsRegex($methods_regex);
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

    private function processMethods($methods) {
        foreach($methods as &$method) {
            $method = $this->dictCode->translate(array_unique($method), Dictionary::CASE_INSENSITIVE);
        }
        unset($method);
        $methods = array_filter($methods);

        if (empty($methods)) {
            return;
        }

        $this->atomIs('Class')
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
    }

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
}

?>
