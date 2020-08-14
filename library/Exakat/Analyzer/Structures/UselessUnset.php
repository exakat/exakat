<?php declare(strict_types = 1);
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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UselessUnset extends Analyzer {
    public function analyze(): void {
        // unset on arguments, reference or value
        $this->atomIs('Unset')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->isArgument()
             ->back('first');
        $this->prepareQuery();

        // unset on global
        $this->atomIs('Unset')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Global')
             ->outIs('GLOBAL')
             ->samePropertyAs('code', 'varname', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // unset on static
        $this->atomIs('Unset')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Static')
             ->outIs('STATIC')
             ->samePropertyAs('code', 'varname', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // unset on foreach (variable or property)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs(array('Variable', 'Member'))
             ->outIsIE('OBJECT')
             ->savePropertyAs('code', 'varname')
             ->inIsIE('OBJECT')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Unset')
             ->as('result')
             ->outIs('ARGUMENT')
             ->outIsIE('OBJECT')
             ->samePropertyAs('code', 'varname', self::CASE_SENSITIVE)
             ->inIsIE('OBJECT')
             ->not(
                $this->side()
                     ->outIs('OBJECT')
                     ->atomIs('Member')
             )
             ->back('result');
        $this->prepareQuery();

        // unset on foreach (KeyVal -> value)
        $this->atomIs('Foreach')
             ->outIs('INDEX')
             ->atomIs(array('Variable', 'Member'))
             ->outIsIE('OBJECT')        // Case it is a property...
             ->savePropertyAs('code', 'varname')
             ->inIsIE('OBJECT')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Unset')
             ->as('result')
             ->outIs('ARGUMENT')
             ->outIsIE('OBJECT')
             ->samePropertyAs('code', 'varname', self::CASE_SENSITIVE)
             ->inIsIE('OBJECT')
             ->not(
                $this->side()
                     ->outIs('OBJECT')
                     ->atomIs('Member')
             )
             ->back('result');
        $this->prepareQuery();

    // unset as operator
        // unset on arguments, reference or value
        $this->atomIs('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->outIs('CAST')
             ->atomIs('Variable')
             ->isArgument()
             ->back('first');
        $this->prepareQuery();

        // unset on global
        $this->atomIs('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->outIs('CAST')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Global')
             ->outIs('GLOBAL')
             ->samePropertyAs('code', 'varname', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // unset on static
        $this->atomIs('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->outIs('CAST')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Static')
             ->outIs('STATIC')
             ->samePropertyAs('code', 'varname', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // unset on foreach (variable)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'varname')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->as('result')
             ->outIs('CAST')
             ->samePropertyAs('code', 'varname', self::CASE_SENSITIVE)
             ->back('result');
        $this->prepareQuery();

        // unset on foreach (KeyVal)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')
             ->outIs('VALUE')
             ->savePropertyAs('code', 'varname')
             ->inIs('VALUE')
             ->inIs('VALUE')
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->as('result')
             ->outIs('CAST')
             ->samePropertyAs('code', 'varname', self::CASE_SENSITIVE)
             ->back('result');
        $this->prepareQuery();
    }
}

?>
