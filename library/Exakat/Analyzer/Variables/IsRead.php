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


namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

class IsRead extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor',
                    );
    }
    
    public function analyze() {
        $this->atomIs(self::$VARIABLES_ALL)
             ->hasIn(array('NOT', 'AT', 'OBJECT', 'NEW', 'RETURN', 'CONCAT', 'SOURCE', 'CODE', 'INDEX', 'CONDITION', 'THEN', 'ELSE',
                           'INDEX', 'VALUE', 'MEMBER', 'METHOD', 'VARIABLE', 'SIGN', 'THROW', 'CAST',
                           'CASE', 'CLONE', 'FINAL', 'CLASS', 'GLOBAL', 'PPP'));
        $this->prepareQuery();

        $this->atomIs(self::$VARIABLES_ALL)
             ->inIs('NAME')
             ->atomIsNot('Parameter')
             ->back('first');
        $this->prepareQuery();

        // Reading inside an assignation
        $this->atomIs(self::$VARIABLES_ALL)
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->hasIn(array('NOT', 'AT', 'OBJECT', 'NEW', 'RETURN', 'CONCAT', 'SOURCE', 'CODE', 'INDEX', 'CONDITION', 'THEN', 'ELSE',
                           'INDEX', 'VALUE', 'NAME', 'MEMBER', 'METHOD', 'VARIABLE', 'SIGN', 'THROW', 'CAST',
                           'CASE', 'CLONE', 'FINAL', 'CLASS', 'PPP'))
             ->back('first');
            // note : NAME is for Switch!!
        $this->prepareQuery();

        // $this is always read
        $this->atomIs('This');
        $this->prepareQuery();

        // right or left, same
        $this->atomIs('Variable')
             ->inIs(array('RIGHT', 'LEFT'))
             ->atomIs(array('Addition', 'Multiplication', 'Logical', 'Comparison', 'Bitshift'))
             ->back('first');
        $this->prepareQuery();

        // right only
        $this->atomIs('Variable')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->back('first');
        $this->prepareQuery();

        // $x++ + 2 (a plusplus within another
        $this->atomIs('Variable')
             ->inIs(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->inIs(array('RIGHT', 'LEFT'))
             ->atomIs(array('Addition', 'Multiplication', 'Logical', 'Comparison', 'Bitshift', 'Assignation'))
             ->back('first');
        $this->prepareQuery();

        // $x++ + 2 (a plusplus in a functioncall
        $this->atomIs('Variable')
             ->inIs(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->hasIn('ARGUMENT')
             ->back('first');
        $this->prepareQuery();

        // variable in a sequence (also useless...)
        $this->atomIs('Variable')
             ->inIs('EXPRESSION')
             ->atomIs('Sequence')
             ->back('first');
        $this->prepareQuery();

        // array only
        $this->atomIs('Variable')
             ->inIs('VARIABLE')
             ->atomIs(array('Array', 'Arrayappend'))
             ->back('first');
        $this->prepareQuery();

        // arguments : normal variable in a custom function
        $this->atomIs('Functioncall')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->savePropertyAs('rank', 'rank')
             ->_as('results')
             ->back('first')
             ->functionDefinition()
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', self::CASE_SENSITIVE)
             ->isNot('reference', true)
             ->back('results');
        $this->prepareQuery();

        $this->atomFunctionIs(array('Functioncall', 'Methodcallname', 'Newcall', 'Exit', 'Echo', 'Print'))
             ->outIs('ARGUMENT')
             ->atomIs('Variable');
        $this->prepareQuery();

        // Variable that are not a reference in a functioncall
        $this->atomIs('Variable')
             ->analyzerIsNot('self')
             ->hasIn('ARGUMENT')
             ->hasNoParent(self::$FUNCTIONS_ALL,  'ARGUMENT');
        $this->prepareQuery();
    }
}

?>
