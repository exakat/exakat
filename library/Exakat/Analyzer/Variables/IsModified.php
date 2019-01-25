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


namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\GroupBy;

class IsModified extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor',
                    );
    }
    
    public function analyze() {
        $atoms = array('Variable',
                       'Phpvariable',
                       'Variablearray',
                       'Parametername',
                      );

        $this->atomIs(array('Variablearray', 'Variable'))
             ->inIsIE('VARIABLE')
             ->hasIn(array('PREPLUSPLUS', 'POSTPLUSPLUS'));
        $this->prepareQuery();

        // (unset)
        $this->atomIs($atoms)
             ->inIs('CAST')
             ->tokenIs('T_UNSET_CAST')
             ->back('first');
        $this->prepareQuery();

        // unset
        $this->atomIs('Unset')
             ->outIs('ARGUMENT')
             ->outIsIE('VARIABLE')
             ->atomIs($atoms);
        $this->prepareQuery();

        $this->atomIs(array('Variablearray', 'Variable', 'Phpvariable'))
             ->inIsIE(array('VARIABLE', 'APPEND'))
             ->inIs(array('LEFT', 'VARIABLE'))
             ->atomIs(array('Assignation', 'Arrayappend'))
             ->hasNoIn('VARIABLE')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE(array('INDEX', 'VALUE'))
             ->atomIs('Variable');
        $this->prepareQuery();

        // catch
        $this->atomIs($atoms)
             ->inIs('VARIABLE')
             ->atomIs('Catch')
             ->back('first');
        $this->prepareQuery();

        // arguments : reference variable in a custom function
        $this->atomIs(array('Functioncall', 'Methodcall', 'Staticmethodcall'))
             ->hasIn('DEFINITION')
             ->hasNoIn('METHOD') // possibly new too
             ->outIsIE('METHOD')
             ->outIs('ARGUMENT')
             ->atomIs($atoms)
             ->savePropertyAs('rank', 'ranked')
             ->_as('results')
             ->back('first')
             ->inIs('DEFINITION')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'ranked', self::CASE_SENSITIVE)
             ->is('reference', true)
             ->back('results');
        $this->prepareQuery();

        
        // arguments : reference variable in a custom function
        $this->atomIs('List')
             ->outIs('ARGUMENT')
             ->atomInside($atoms);
        $this->prepareQuery();

        // function/methods definition : all modified by incoming values
        // simple variable
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('NAME')
             ->atomIs($atoms);
        $this->prepareQuery();

        // simple variable + default value : already done in line 18

        // typehint
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->atomIs($atoms);
        $this->prepareQuery();

        // PHP functions that are using references
        $functions = self::$methods->getFunctionsReferenceArgs();
        $references = new GroupBy();
        
        foreach($functions as $function) {
            $references[$function['position']] = "\\$function[function]";
        }
        
        foreach($references as $position => $functions) {
            $this->atomFunctionIs($functions)
                 ->outIs('ARGUMENT')
                 ->is('rank', $position)
                 ->outIsIE('VARIABLE')
                 ->atomIs(self::$VARIABLES_ALL);
            $this->prepareQuery();
        }

        // Class constructors (__construct)
        $this->atomIs('New')
             ->outIs('NEW')
             ->atomIs('Newcall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->outIs('ARGUMENT')
             ->atomIs($atoms)
             ->savePropertyAs('rank', 'ranked')
             ->_as('results')
             ->inIs('ARGUMENT')
             ->classDefinition()
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->analyzerIs('Classes/Constructor')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'ranked')
             ->is('reference', true)
             ->back('results');
        $this->prepareQuery();
    }
}

?>
