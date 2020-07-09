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

namespace Exakat\Analyzer\Typehints;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

abstract class CouldBeType extends Analyzer {
    public function dependsOn() : array {
        return array('Complete/PropagateConstants',
                     'Complete/CreateDefaultValues',
                    );
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    //  Argument types
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    protected function checkArgumentDefaultValues(array $atoms = array()) : void { 
        // foo($b = array())
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->filter(
                 $this->side()
                      ->outIs('TYPEHINT')
                      ->atomIs('Void')
              )
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('DEFAULT')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->back('result');
        $this->prepareQuery();
    }
    
    protected function checkArgumentUsage(array $atoms = array()) : void { 
        // foo($b) { $b[] = 1;}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->filter(
                 $this->side()
                      ->outIs('TYPEHINT')
                      ->atomIs('Void')
              )
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->back('result');
        $this->prepareQuery();
    }

    protected function checkRelayedArgument(array $atoms = array(), array $fullnspath = array()) : void { 
        // foo($b) { bar($b)} ; function bar(array $c) {}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->filter(
                 $this->side()
                      ->outIs('TYPEHINT')
                      ->atomIs('Void')
              )
             ->as('result')
             ->analyzerIsNot('self')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->savePropertyAs('rank', 'ranked')
             ->inIs('ARGUMENT')
             ->inIs('DEFINITION')
             ->outWithRank('ARGUMENT', 'ranked')
             ->outIs('TYPEHINT')
             ->atomIs($atoms)
             ->fullnspathIs($fullnspath)
             ->back('result');
        $this->prepareQuery();
    }

    protected function checkRelayedArgumentToPHP(string $type = '') : void { 
        // foo($b) { bar($b)} ; function bar(array $c) {}
        $ini = $this->methods->getFunctionsByArgType($type, Methods::LOOSE);
        
        if (empty($ini)) {
            return;
        }

        foreach($ini as $rank => $functions) {
            // foo($arg) { array_map($arg, '') ; }
            $this->atomIs('Parameter')
                 ->analyzerIsNot('self')
                 ->filter(
                    $this->side()
                         ->outIs('TYPEHINT')
                         ->atomIs('Void')
                 )
                 ->outIs('NAME')
                 ->outIs('DEFINITION')
                 ->is('rank', (int) $rank)
                 ->inIs('ARGUMENT')
                 ->functioncallIs($functions)
                 ->back('first');
            $this->prepareQuery();
        }
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    //  Return types
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    protected function checkReturnedAtoms(array $atoms = array()) : void { 
        // return array(1,2,3)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNED')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->back('first');
        $this->prepareQuery();
    }

    protected function checkReturnedTypehint(array $atoms = array(), array $fullnspath = array()) : void { 
        // function foo (array $a) { return $a;}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNED')
             ->atomIs('Variable', self::WITH_CONSTANTS)
             ->inIs('DEFINITION')
             ->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs($atoms)
             ->fullnspathIs($fullnspath)
             ->back('first');
        $this->prepareQuery();
    }

    protected function checkReturnedDefault(array $atoms = array()) : void { 
        // return array(1,2,3)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNED')
             ->atomIs('Variable', self::WITH_CONSTANTS)
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->atomIs('Parameterdefinition')
             ->outIs('DEFAULT')
             ->atomIs($atoms)
             ->back('first');
        $this->prepareQuery();
    }

    protected function checkReturnedCalls(array $atoms = array(), array $fullnspath = array()) : void { 
        // return array(1,2,3)
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNED')
             ->atomIs(self::CALLS, self::WITH_VARIABLES)
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')
             ->atomIs($atoms)
             ->fullnspathIs($fullnspath)
             ->back('first');
        $this->prepareQuery();
    }

    protected function checkReturnedPHPTypes(string $type = '') : void { 
        // foo($b) { bar($b)} ; function bar(array $c) {}
        $ini = $this->methods->getFunctionsByReturnType($type, Methods::LOOSE);

        if (empty($ini)) {
            return;
        }

        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNED')
             ->atomIs('Functioncall', self::WITH_VARIABLES)
             ->fullnspathIs($ini)
             ->back('first');
        $this->prepareQuery();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    //  Property types
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    // class x { protected $p = array(); }
    // class x { private $p; function foo() {$this->p = array();  } }
    protected function checkPropertyDefault(array $atoms = array()) : void { 
            $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFAULT')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->back('first');
        $this->prepareQuery();
    }

    protected function checkPropertyRelayedDefault(array $atoms = array()) : void { 
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFAULT')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('DEFAULT')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->back('first');
        $this->prepareQuery();
    }

    protected function checkPropertyRelayedTypehint(array $atoms = array(), array $fullnspath = array()) : void { 
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFAULT')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->outIs('TYPEHINT')
             ->atomIs($atoms)
             ->fullnspathIs($fullnspath)
             ->back('first');
        $this->prepareQuery();
    }

    protected function checkPropertyWithCalls(array $atoms = array(), array $fullnspath = array()) : void { 
        // class x { private $p; function foo() { $this->p = bar(); }} function bar() : array {}
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFAULT')
             ->atomIs(self::CALLS, self::WITH_VARIABLES)
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')
             ->atomIs($atoms)
             ->fullnspathIs($fullnspath)
             ->back('first');
        $this->prepareQuery();
    }

    protected function checkPropertyWithPHPCalls(string $type = '') : void { 
        // foo($b) { bar($b)} ; function bar(array $c) {}
        $ini = $this->methods->getFunctionsByReturnType($type, Methods::LOOSE);

        if (empty($ini)) {
            return;
        }

        $this->atomIs('Propertydefinition')
             ->analyzerIsNot('self')
             ->outIs('DEFAULT')
             ->atomIs('Functioncall', self::WITH_VARIABLES)
             ->fullnspathIs($ini)
             ->back('first');
        $this->prepareQuery();
    }

    protected function checkArgumentValidation(array $filters = array(), array $atoms = array()) : void {
        // is_array($arg)
        if (!empty($filters)) {
            $this->atomIs(self::FUNCTIONS_ALL)
                 ->outIs('ARGUMENT')
                 ->analyzerIsNot('self')
                 ->as('result')
                 ->outIs('NAME')
                 ->outIs('DEFINITION')
                 ->inIs('ARGUMENT')
                 ->functioncallIs($filters)
                 ->back('result');
            $this->prepareQuery();
        }

        // comparison
        if (!empty($atoms)) {
            $this->atomIs(self::FUNCTIONS_ALL)
                 ->outIs('ARGUMENT')
                 ->analyzerIsNot('self')
                 ->as('result')
                 ->outIs('NAME')
                 ->outIs('DEFINITION')
                 ->inIs(array('LEFT', 'RIGHT'))
                 ->atomIs('Comparison')
                 ->inIs(array('LEFT', 'RIGHT'))
                 ->atomIs($atoms, self::WITH_VARIABLES)
                 ->back('result');
            $this->prepareQuery();
        }
    }
    
    protected function checkCastArgument(string $token = '', array $functions = array()) : void {
        // (string) $arg
        if (!empty($token)) {
            $this->atomIs(self::FUNCTIONS_ALL)
                 ->outIs('ARGUMENT')
                 ->analyzerIsNot('self')
                 ->as('result')
                 ->outIs('NAME')
                 ->outIs('DEFINITION')
                 ->inIs('CAST')
                 ->atomIs('Cast')
                 ->tokenIs($token)
                 ->back('result');
            $this->prepareQuery();
        }

        // conversion
        if (!empty($functions)) {
            $this->atomIs(self::FUNCTIONS_ALL)
                 ->outIs('ARGUMENT')
                 ->analyzerIsNot('self')
                 ->as('result')
                 ->outIs('NAME')
                 ->outIs('DEFINITION')
                 ->inIs('ARGUMENT')
                 ->back('result');
            $this->prepareQuery();
        }
    }
}

?>
