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

class NoEmptyRegex extends Analyzer {
    public static $pregFunctions = array('\\preg_match_all',
                                         '\\preg_match',
                                         '\\preg_replace',
                                         '\\preg_replace_callback',
                                         '\\preg_relace_callback_array',
                                         );

    public function analyze(): void {
        // preg_match(''.$b, $d, $d); Empty delimiter
        $this->atomFunctionIs(self::$pregFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(self::STRINGS_ALL, self::WITH_CONSTANTS)
             ->outIsIE('CONCAT')
             ->tokenIs(array('T_CONSTANT_ENCAPSED_STRING', 'T_ENCAPSED_AND_WHITESPACE'))
             ->noDelimiterIs('')
             ->back('first');
        $this->prepareQuery();

        // preg_match('a'.$b, $d, $d); Non-alpha numerical delimiter
        $this->atomFunctionIs(self::$pregFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(self::STRINGS_ALL, self::WITH_CONSTANTS)
             ->outWithRank('CONCAT', 0)
             ->outIsIE('CONCAT') // keep going in case
             ->is('rank', 0)
             ->tokenIs(array('T_CONSTANT_ENCAPSED_STRING', 'T_ENCAPSED_AND_WHITESPACE'))
             ->noDelimiterIsNot('')
             ->regexIs('noDelimiter', '^[A-Za-z0-9]')
             ->back('first');
        $this->prepareQuery();

        // preg_match('abc', $d, $d); Non-alpha numerical delimiter
        $this->atomFunctionIs(self::$pregFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(self::STRINGS_ALL, self::WITH_CONSTANTS)
             ->hasNoOut('CONCAT')
             ->noDelimiterIsNot('')
             ->regexIs('noDelimiter', '^[A-Za-z0-9]')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
