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


namespace Analyzer\Variables;

use Analyzer;

class IsModified extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\Constructor');
    }
    
    public function analyze() {
        $this->atomIs('Variable')
             ->inIsIE('VARIABLE')
             ->hasIn(array('PREPLUSPLUS', 'POSTPLUSPLUS', 'DEFINE', 'CAST'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Variable')
             ->inIsIE('VARIABLE')
             ->inIs(array('LEFT', 'VARIABLE'))
             ->atomIs(array('Assignation', 'Arrayappend'))
             ->hasNoIn('VARIABLE')
             ->back('first');
        $this->prepareQuery();

        // catch
        $this->atomIs('Variable')
             ->inIs('VARIABLE')
             ->atomIs(array('Catch'))
             ->back('first');
        $this->prepareQuery();

        // arguments : reference variable in a custom function
        $this->atomIs('Variable')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->hasNoIn('METHOD') // possibly new too
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', true)
             ->is('reference', true)
             ->back('first');
        $this->prepareQuery();

        // function/methods definition : all modified by incoming values
        // simple variable
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable');
        $this->prepareQuery();

        // simple variable + default value : already done in line 18

        // typehint
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('VARIABLE')
             ->atomIs('Variable');
        $this->prepareQuery();

        // typehint + default value
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('VARIABLE')
             ->atomIs('Assignation')
             ->outIs('LEFT');
        $this->prepareQuery();

        // missing default values + typehint + default values.

        // PHP functions that are references
        $data = new \Data\Methods();
        
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
            $this->atomIs('Variable')
                 ->inIsIE('VARIABLE')
                 ->is('rank', $position)
                 ->inIs('ARGUMENT')
                 ->inIs('ARGUMENTS')
                 ->hasNoIn('METHOD') // possibly new too
                 ->atomIs('Functioncall')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR', 'T_UNSET'))
                 ->fullnspath($functions)
                 ->back('first');
            $this->prepareQuery();
        }

        // Class constructors (__construct)
        $this->atomIs('Variable')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->hasNoIn('METHOD') // possibly new too
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIs('Functioncall')
             ->hasIn('NEW')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')
             ->is('reference', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
