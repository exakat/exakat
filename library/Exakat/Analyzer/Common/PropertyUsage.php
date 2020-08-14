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

class PropertyUsage extends Analyzer {
    protected $properties = array();

    public function analyze(): void {
        $staticHash = array();
        $propertyHash = array();
        foreach($this->properties as $class => $properties) {
            foreach($properties as $property => $details) {
                if (!isset($details['fullname'])) {
                    continue;
                }
                array_collect_by($staticHash, $class, $details['fullname']);

                array_collect_by($propertyHash, $class, $property);
            }
        }

        // A::$property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->fullnspathIs(array_keys($staticHash))
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->outIs('MEMBER')
             ->isHash('fullcode', $staticHash, 'fnp')
             ->back('first');
        $this->prepareQuery();

        // $a = new C; $a->property
        $this->atomIs('Member')
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->inIs('DEFINITION')
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('New')
             ->outIs('NEW')
             ->fullnspathIs(array_keys($propertyHash))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('MEMBER')
             ->isHash('fullcode', $propertyHash, 'fnp')
             ->back('first');
        $this->prepareQuery();

        // function foo() : C; $a = foo(); $a->property
        $this->atomIs('Member')
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
             ->atomIsNot('Void')
             ->fullnspathIs(array_keys($propertyHash))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('MEMBER')
             ->isHash('fullcode', $propertyHash, 'fnp')
             ->back('first');
        $this->prepareQuery();

        // function foo(C $a) { $a->property; }
        $this->atomIs('Member')
             ->analyzerIsNot('self')
             ->outIs('OBJECT')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->fullnspathIs(array_keys($propertyHash))
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('MEMBER')
             ->isHash('fullcode', $propertyHash, 'fnp')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
