<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Data\Methods;

class IsModified extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor');
    }
    
    public function analyze() {
        // $a[3]++;
        $this->atomIs('Array')
             ->hasIn(array('PREPLUSPLUS', 'POSTPLUSPLUS', 'DEFINE', 'CAST'))
             ->back('first');
        $this->prepareQuery();

        // $a[1] = 2;
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->back('first')
             ->raw('where( __.repeat( __.out("VARIABLE")).emit(hasLabel("Arrayappend")).times('.self::MAX_LOOPING.').count().is(eq(0)) )')
             ;
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
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->savePropertyAs('rank', 'rank')
             ->_as('results')
             ->back('first')
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', self::CASE_SENSITIVE)
             ->is('reference', self::CASE_SENSITIVE)
             ->back('results');
        $this->prepareQuery();

        // function/methods definition : all modified by incoming values
        // simple variable
        $this->atomIs(self::$FUNCTION_METHOD)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array');
        $this->prepareQuery();

        // PHP functions that are references
        $data = new Methods($this->config);
        
        $functions = $data->getFunctionsReferenceArgs();
        $references = array();
        
        foreach($functions as $function) {
            if (!isset($references[$function['position']])) {
                $references[$function['position']] = array('\\'.$function['function']);
            } else {
                $references[$function['position']][] = '\\'.$function['function'];
            }
        }
        
        foreach($references as $position => $functions) {
            $this->atomFunctionIs($functions)
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', $position);
            $this->prepareQuery();
        }

        // Class constructors (__construct)
        $this->atomIs('Functioncall')
             ->hasIn('NEW')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->savePropertyAs('rank', 'rank')
             ->back('first')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->analyzerIs('Classes/Constructor')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')
             ->is('reference', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
