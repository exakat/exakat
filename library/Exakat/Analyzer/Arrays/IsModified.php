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


namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class IsModified extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor',
                    );
    }
    
    public function analyze() {
        // $a[3]++;
        $this->atomIs('Array')
             ->hasIn(array('PREPLUSPLUS', 'POSTPLUSPLUS', 'CAST'))
             ->raw('not(where( __.in("CAST").has("token", "T_UNSET_CAST") ) )')
             ->back('first');
        $this->prepareQuery();

        // $a[1] = 2;
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->back('first')
             ->noAtomInside('Arrayappend');
        $this->prepareQuery();

        // foreach($a as $b[2])
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Array')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $b[2])
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs(array('INDEX', 'VALUE'))
             ->atomIs('Array')
             ->back('first');
        $this->prepareQuery();

        // $a[1][] = 2;
        $this->atomIs('Array')
             ->inIs('APPEND')
             ->atomIs('Arrayappend')
             ->back('first');
        $this->prepareQuery();

        // arguments : reference variable in a custom function
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->hasNoIn('METHOD') // possibly new too
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->savePropertyAs('rank', 'rank')
             ->_as('results')
             ->back('first')
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', self::CASE_SENSITIVE)
             ->is('reference', self::CASE_SENSITIVE)
             ->back('results');
        $this->prepareQuery();

        // function/methods definition : all modified by incoming values
        // simple variable
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->atomIs('Array');
        $this->prepareQuery();

        // PHP functions that are references
        $functions = $this->methods->getFunctionsReferenceArgs();
        $references = array();
        
        foreach($functions as $function) {
            if (isset($references[$function['position']])) {
                $references[$function['position']][] = '\\'.$function['function'];
            } else {
                $references[$function['position']] = array('\\'.$function['function']);
            }
        }
        
        foreach($references as $position => $functions) {
            $this->atomFunctionIs($functions)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->atomIs('Array');
            $this->prepareQuery();
        }

        $this->atomIs('Unset')
             ->outIs('ARGUMENT')
             ->atomIs('Array');
        $this->prepareQuery();

        // Class constructors (__construct)
        $this->atomIs('Newcall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->savePropertyAs('rank', 'rank')
             ->back('first')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->analyzerIs('Classes/Constructor')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')
             ->is('reference', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
