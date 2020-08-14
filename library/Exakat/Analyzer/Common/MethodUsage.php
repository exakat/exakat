<?php declare(strict_types = 1);
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


namespace Exakat\Analyzer\Common;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Dictionary;

class MethodUsage extends Analyzer {
    protected $methodList = array();

    public function analyze(): void {
        $staticHash = array();
        $methodHash = array();
        foreach($this->methodList as $class => $methods) {
            foreach($methods as $details) {
                if (isset($staticHash[$class])) {
                    $staticHash[$class][] = $details->normal_name;
                } else {
                    $staticHash[$class] = array($details->normal_name);
                }

                if (isset($methodHash[$class])) {
                    $methodHash[$class][] = $details->normal_name;
                } else {
                    $methodHash[$class] = array($details->normal_name);
                }
            }
        }

        foreach($staticHash as &$methods) {
            $methods = $this->dictCode->translate(array_unique($methods), Dictionary::CASE_INSENSITIVE);
        }
        unset($methods);
        foreach($methodHash as &$methods) {
            $methods = $this->dictCode->translate(array_unique($methods), Dictionary::CASE_INSENSITIVE);
        }
        unset($methods);

        // A::method()
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->fullnspathIs(array_keys($staticHash))
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->isHash('lccode', $staticHash, 'fnp')
             ->back('first');
        $this->prepareQuery();

        // $a = new C; $a->method()
        $this->atomIs('Methodcall')
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->inIs('DEFINITION')
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('New')
             ->outIs('NEW')
             ->fullnspathIs(array_keys($methodHash))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('METHOD')
             ->isHash('lccode', $methodHash, 'fnp')
             ->back('first');
        $this->prepareQuery();

        // function foo() : C; $a = foo(); $a->method()
        $this->atomIs('Methodcall')
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->inIs('DEFINITION')
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs(array('Functioncall', 'Staticmethodcall', 'Methodcall'))
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')
             ->fullnspathIs(array_keys($methodHash))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('METHOD')
             ->isHash('lccode', $methodHash, 'fnp')
             ->back('first');
        $this->prepareQuery();

        // function foo(C $a) { $a->method(); }
        $this->atomIs('Methodcall')
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('TYPEHINT')
             ->fullnspathIs(array_keys($methodHash))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('METHOD')
             ->isHash('lccode', $methodHash, 'fnp')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
