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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class CouldBePrivateMethod extends Analyzer {
    public function dependsOn() {
        return array('Classes/MethodUsedBelow',
                     'Classes/IsNotFamily',
                    );
    }
    
    public function analyze() {
        // Searching for methods that are never used outside the definition class

        // Non-static methods
        // Case of object->method() (that's another public access)
        $this->atomIs('Methodcall')
             ->not(
                $this->side()
                     ->outIs('OBJECT')
                     ->atomIs('This')
             )
             ->outIs('METHOD')
             ->atomIs('Methodcallname')
             ->values('lccode')
             ->unique();
        $publicMethods = $this->rawQuery()
                              ->toArray();

        $this->atomIs('Method')
             ->isNot('visibility', 'private')
             ->isNot('static', true)
             ->analyzerIsNot('Classes/MethodUsedBelow')
             ->outIs('NAME')
             ->codeIsNot($publicMethods, self::NO_TRANSLATE)
             ->back('first');
        $this->prepareQuery();

        // Static methods
        // Case of class::method() (that's another public access)
        $this->atomIs('Staticmethodcall')
             ->analyzerIs('Classes/IsNotFamily')
             ->outIs('CLASS')
             ->atomIs(array('Identifier', 'Nsname'))
             ->_as('classe')
             ->savePropertyAs('fullnspath', 'fns')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->atomIs('Methodcallname')
             ->savePropertyAs('code', 'name')
             ->_as('method')
//             ->goToClass()
             ->select(array('classe' => 'fullnspath',
                            'method' => 'lccode',
                            ))
             ->unique();
        $publicStaticMethods = $this->rawQuery()
                                    ->toArray();

        if (!empty($publicStaticMethods)) {
            $calls = array();
            foreach($publicStaticMethods as $value) {
                if (isset($calls[$value['method']])) {
                    $calls[$value['method']][] = $value['classe'];
                } else {
                    $calls[$value['method']] = array($value['classe']);
                }
            }

            // Property that is not used outside this class or its children
            $this->atomIs('Method')
                 ->isNot('visibility', 'private')
                 ->is('static', true)
                 ->analyzerIsNot('Classes/MethodUsedBelow')
                 ->_as('results')
                 ->codeIsNot(array_keys($calls), self::NO_TRANSLATE)
                 ->savePropertyAs('code', 'variable')
                 ->goToClass()
                 ->isNotHash('fullnspath', $calls, 'variable')
                 ->back('results');
            $this->prepareQuery();
        }
    }
}

?>
