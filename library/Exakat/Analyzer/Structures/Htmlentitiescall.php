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
use Exakat\Query\DSL\FollowParAs;

class Htmlentitiescall extends Analyzer {
    public function analyze(): void {
        $html_functions = array('\\htmlentities',
                                '\\htmlspecialchars',
                               );

        // Case with no 2nd argument (using default)
        $this->atomFunctionIs($html_functions)
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        // Case with no 3rd argument (using default)
        $this->atomFunctionIs($html_functions)
             ->hasChildWithRank('ARGUMENT', 1)
             ->noChildWithRank('ARGUMENT', 2)
             ->back('first');
        $this->prepareQuery();

        $constants = $this->loadIni('htmlentities_constants.ini', 'constants');
        $constants = makeFullNsPath($constants, \FNP_CONSTANT);

        // Case 2nd argument is a constant
        $this->atomFunctionIs($html_functions)
             ->outWithRank('ARGUMENT', 1)
             ->atomIs(array('Identifier', 'Nsname'))
             ->hasChildWithRank('ARGUMENT', 2)
             ->fullnspathIsNot($constants)
             ->back('first');
        $this->prepareQuery();

        // Case 2nd argument is a combinaison
        $this->atomFunctionIs($html_functions)
             ->hasChildWithRank('ARGUMENT', 2)
             ->outWithRank('ARGUMENT', 1)
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIs('Bitoperation')
             ->outIsIE(array('LEFT', 'RIGHT', 'CODE'))
             ->atomIs(array('Identifier', 'Nsname'))
             ->fullnspathIsNot($constants, self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // Case 3rd argument is one of the following value
        $htmlentities_constants = $this->loadIni('htmlentities_constants.ini', 'encoding');
        $this->atomFunctionIs($html_functions)
             ->hasChildWithRank('ARGUMENT', 2)
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('String')
             ->noDelimiterIsNot($htmlentities_constants, self::CASE_INSENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
