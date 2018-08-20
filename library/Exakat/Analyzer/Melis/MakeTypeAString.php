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

namespace Exakat\Analyzer\Melis;

use Exakat\Analyzer\Analyzer;

class MakeTypeAString extends Analyzer {
    public function analyze() {
    /*
    'conf' => array(
        'type' => NOT A STRING
    )
    */
        $this->atomIs('String')
             ->noDelimiterIs('conf', self::CASE_SENSITIVE)
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->outIs('INDEX')
             ->noDelimiterIs('type')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIsNot(array('String', 'Identifier', 'Nsname', 'Staticconstant'))
             ;
        $this->prepareQuery();

    /*
    const CONSTANT = [1,2,3];

    'conf' => array(
        'type' => CONSTANT
    )
    */
        $this->atomIs('String')
             ->noDelimiterIs('conf')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->outIs('INDEX')
             ->noDelimiterIs('type')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs(array('Identifier', 'Nsname'))
             ->_as('results')
             ->inIs('DEFINITION')
             ->outIs('VALUE')
             ->atomIsNot('String')
             ->back('results');
        $this->prepareQuery();

    /*
    const CONSTANT = [1,2,3];

    'conf' => array(
        'type' => CONSTANT
    )
    */
        $this->atomIs('String')
             ->noDelimiterIs('conf')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->outIs('INDEX')
             ->noDelimiterIs('type')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('Staticconstant')
             ->_as('results')
             ->inIs('DEFINITION')
             ->outIs('VALUE')
             ->atomIsNot('String')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
