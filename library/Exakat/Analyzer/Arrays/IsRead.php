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

class IsRead extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor',
                    );
    }
    
    public function analyze() {
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE')
             ->hasIn(array('NOT', 'AT', 'OBJECT', 'NEW', 'RETURN', 'CONCAT', 'SOURCE', 'CODE', 'CONDITION', 'THEN', 'ELSE',
                           'INDEX', 'VALUE', 'NAME', 'MEMBER', 'METHOD', 'VARIABLE', 'SIGN', 'THROW', 'CAST',
                           'CASE', 'CLONE', 'FINAL', 'CLASS'));
            // note : NAME is for Switch!!
        $this->prepareQuery();

        // right or left, same
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE')
             ->inIs(array('RIGHT', 'LEFT'))
             ->atomIs(array('Addition', 'Multiplication', 'Logical', 'Comparison', 'Bitshift'))
             ->back('first');
        $this->prepareQuery();

        // right only
        $this->atomIs('Array')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->back('first');
        $this->prepareQuery();

        // $x++ + 2 (a plusplus within another
        $this->atomIs('Array')
             ->inIs(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->inIs(array('RIGHT', 'LEFT'))
             ->atomIs(array('Addition', 'Multiplication', 'Logical', 'Comparison', 'Bitshift', 'Assignation'))
             ->back('first');
        $this->prepareQuery();

        // $x++ + 2 (a plusplus in a functioncall
        $this->atomIs('Array')
             ->inIs(array('PREPLUSPLUS', 'POSTPLUSPLUS'))
             ->hasIn('ARGUMENT')
             ->back('first');
        $this->prepareQuery();

        // variable in a sequence (also useless...)
        $this->atomIs('Array')
             ->inIs('EXPRESSION')
             ->atomIs('Sequence')
             ->back('first');
        $this->prepareQuery();

        // array only
        $this->atomIs('Array')
             ->inIs('VARIABLE')
             ->atomIs(array('Array', 'Arrayappend'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs(array('Functioncall', 'Methodcallname', 'Newcall', 'Exit', 'Echo', 'Print'))
             ->outIs('ARGUMENT')
             ->atomIs('Array');
        $this->prepareQuery();

        // Class constructors (__construct)
        $this->atomIs('Array')
             ->hasIn('ARGUMENT')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->atomIs('Functioncall')
             ->hasIn('NEW')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->_as('method')
             ->analyzerIs('Classes/Constructor')
             ->back('method')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', self::CASE_SENSITIVE)
             ->isNot('reference', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // Class constructors with self
        $this->atomIs('Array')
             ->hasIn('ARGUMENT')
             ->savePropertyAs('rank', 'rank')
             ->inIs('ARGUMENT')
             ->atomIs('Functioncall')
             ->codeIs('self')
             ->hasIn('NEW')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->_as('method')
             ->outIs('NAME')
             ->analyzerIs('Classes/Constructor')
             ->back('method')
             ->outIs('ARGUMENT')
             ->samePropertyAs('rank', 'rank', self::CASE_SENSITIVE)
             ->isNot('reference', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
