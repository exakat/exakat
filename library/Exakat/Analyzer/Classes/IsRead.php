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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

class IsRead extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor',
                     'Variables/IsRead');
    }
    
    public function analyze() {
        $atoms = array('Property', 'Staticproperty');
        
        $this->atomIs($atoms)
             ->hasIn(array('NOT', 'AT', 'OBJECT', 'NEW', 'RETURN', 'CONCAT', 'SOURCE', 'CODE', 'INDEX', 'CONDITION', 'THEN', 'ELSE',
                           'KEY', 'VALUE', 'NAME', 'DEFINE', 'PROPERTY', 'METHOD', 'VARIABLE', 'SIGN', 'THROW', 'CAST',
                           'CASE', 'CLONE', 'FINAL', 'CLASS', 'GLOBAL'));
            // note : NAME is for Switch!!
        $this->prepareQuery();

        // right or left, same
        $this->atomIs($atoms)
             ->inIs(array('RIGHT', 'LEFT'))
             ->atomIs(array('Addition', 'Multiplication', 'Logical', 'Comparison', 'Bitshift'))
             ->back('first');
        $this->prepareQuery();

        // right only
        $this->atomIs($atoms)
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->back('first');
        $this->prepareQuery();

        // $x++ + 2 (a plusplus within another
        $this->atomIs($atoms)
             ->inIs(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->inIs(array('RIGHT', 'LEFT'))
             ->atomIs(array('Addition', 'Multiplication', 'Logical', 'Comparison', 'Bitshift', 'Assignation'))
             ->back('first');
        $this->prepareQuery();

        // $x++ + 2 (a plusplus in a functioncall
        $this->atomIs($atoms)
             ->inIs(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->hasIn('ARGUMENT')
             ->back('first');
        $this->prepareQuery();

        // variable in a sequence (also useless...)
        $this->atomIs($atoms)
             ->inIs('ELEMENT')
             ->atomIs('Sequence')
             ->back('first');
        $this->prepareQuery();

        // array only
        $this->atomIs($atoms)
             ->inIs('VARIABLE')
             ->atomIs(array('Array', 'Arrayappend'))
             ->back('first');
        $this->prepareQuery();

        // PHP functions that are passed by value
        $data = new Methods();
        
        $functions = $data->getFunctionsValueArgs();
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
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs($atoms);
            $this->prepareQuery();
        }

        // Variable that are not a reference in a functioncall
        $this->atomIs($atoms)
             ->hasIn('ARGUMENT')
             ->raw('where( __.in("ARGUMENT").in("ARGUMENTS").hasLabel("Function").count().is(eq(0)) )')
             ->analyzerIsNot('Variables/IsRead');
        $this->prepareQuery();

        // Class constructors (__construct)
        // Those are done in the functioncall test

        // Class constructors with self
        $this->atomIs($atoms)
             ->hasIn('ARGUMENT')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Functioncall')
             ->codeIs('self')
             ->hasIn('NEW')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->_as('method')
             ->outIs('NAME')
             ->analyzerIs('Classes/Constructor')
             ->back('method')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', true)
             ->isNot('reference', true)
             ->back('first');
        $this->prepareQuery();

        // Class constructors with self
        $this->atomIs($atoms)
             ->hasIn('ARGUMENT')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->atomIs('Functioncall')
             ->codeIs('self')
             ->hasIn('NEW')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->_as('method')
             ->outIs('NAME')
             ->analyzerIs('Classes/Constructor')
             ->back('method')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', true)
             ->isNot('reference', true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
