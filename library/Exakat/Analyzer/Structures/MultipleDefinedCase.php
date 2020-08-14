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

class MultipleDefinedCase extends Analyzer {
    public function analyze(): void {
        // Check that fullcode is the same or not for integers
        $this->atomIs(self::SWITCH_ALL)
             ->filter(
                $this->side()
                     ->outIs('CASES')
                     ->outIs('EXPRESSION')
                     ->atomIs('Case')
                     ->outIs('CASE')
                     ->atomIs(array('Integer', 'Null', 'Boolean'), self::WITH_CONSTANTS)
                     ->raw('groupCount().by("intval").map{ it.get().findAll{ it.value > 1}.size()}.is(gte(1))')
             );
        $this->prepareQuery();

        // Special case for strings (avoiding ' and ")
        $this->atomIs(self::SWITCH_ALL)
             ->analyzerIsNot('self')
             ->filter(
                $this->side()
                     ->outIs('CASES')
                     ->outIs('EXPRESSION')
                     ->atomIs('Case')
                     ->outIs('CASE')
                     ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
                     ->has('noDelimiter')
                     ->raw('groupCount().by("noDelimiter").map{ it.get().findAll{ it.value > 1}.size()}.is(gte(1))')
             );
        $this->prepareQuery();

        // Check that fullcode is the same or not for constants, based on fullnspath
        $this->atomIs(self::SWITCH_ALL)
             ->analyzerIsNot('self')
             ->filter(
                $this->side()
                     ->outIs('CASES')
                     ->outIs('EXPRESSION')
                     ->atomIs('Case')
                     ->outIs('CASE')
                     ->atomIs(array('Nsname', 'Identifier'), self::WITHOUT_CONSTANTS)
                     ->raw('groupCount().by("fullnspath").map{ it.get().findAll{ it.value > 1}.size()}.is(gte(1))')
             );
        $this->prepareQuery();

        // Check that fullcode which are expressions  $a == 1
        $this->atomIs(self::SWITCH_ALL)
             ->analyzerIsNot('self')
             ->filter(
                $this->side()
                     ->outIs('CASES')
                     ->outIs('EXPRESSION')
                     ->atomIs('Case')
                     ->outIs('CASE')
                     ->atomIsNot(array('Nsname', 'Identifier', 'Integer', 'Null', 'Boolean', 'String', 'Concatenation', 'Heredoc' ), self::WITHOUT_CONSTANTS)
                     ->raw('groupCount().by("fullcode").map{ it.get().findAll{ it.value > 1}.size()}.is(gte(1))')
             );
        $this->prepareQuery();

        // Special case for mix of strings and constants
    }
}

?>
