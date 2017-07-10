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

class IsModified extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor');
    }
    
    public function analyze() {
        $atoms = array('Member', 'Staticproperty');

        $this->atomIs($atoms)
             ->hasIn(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs($atoms)
             ->inIs('CAST')
             ->tokenIs('T_UNSET_CAST')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs($atoms)
             ->inIsIE(array('VARIABLE', 'APPEND'))
             ->inIs(array('LEFT', 'APPEND'))
             ->atomIs(array('Assignation', 'Arrayappend'))
             ->back('first');
        $this->prepareQuery();

        // arguments : reference variable in a custom function
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs($atoms)
             ->savePropertyAs('rank', 'rank')
             ->_as('results')
             ->back('first')
             ->functionDefinition()
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', self::CASE_SENSITIVE)
             ->is('reference', self::CASE_SENSITIVE)
             ->back('results');
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
                 ->is('rank', $position)
                 ->atomIs($atoms);
            $this->prepareQuery();
        }

        // Class constructors (__construct)
        $this->atomIs('Newcall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs($atoms)
             ->savePropertyAs('rank', 'rank')
             ->_as('results')
             ->back('first')
             ->classDefinition()
             ->outIs('METHOD')
             ->analyzerIs('Classes/Constructor')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank')
             ->is('reference', self::CASE_SENSITIVE)
             ->back('results');
        $this->prepareQuery();
    }
}

?>
