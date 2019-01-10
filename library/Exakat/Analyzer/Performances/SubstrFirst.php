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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class SubstrFirst extends Analyzer {
    public function analyze() {
        $substrFunctions = array('\substr', '\stristr', '\strstr', '\iconv_substr', '\mb_substr', '\basename', '\dirname',
                                 '\\chop', '\\trim', '\\rtrim', '\\ltrim',);
        $replacingFunctions = array('\\strtolower', '\\strtoupper', '\\strtr', '\\htmlentities', '\\htmlspecialchars', '\\str_replace', '\\str_ireplace', '\\ucfirst', '\\ucwords',
                                    '\\iconv',
                                    '\\mb_string_convert', '\\mb_strtoupper', '\\mb_strtolower', '\\mb_ereg_replace_callback', '\\mb_ereg_replace', '\\mb_eregi_replace', '\\mb_strcut', '\\mb_strimwidth',
                                    '\\preg_replace', '\\preg_relace_callback', '\\preg_replace_calback_array',
                                    );

        // substr(strtolower('a'), 1, 100);
        $this->atomFunctionIs($substrFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIsNot('Concatenation')
             ->atomInsideNoDefinition('Functioncall')
             ->fullnspathIs($replacingFunctions)
             ->back('first');
        $this->prepareQuery();

        // $a = strtolower('a'); substr($a, 1, 100);
        $this->atomFunctionIs($replacingFunctions)
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->_as('results')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'tmp')
             ->inIs('LEFT')
             ->nextSibling()
             ->atomIsNot(array('Ifthen')) // possibly others
             ->atomInsideNoDefinition('Functioncall')
             ->functioncallIs($substrFunctions)
             ->outIs('ARGUMENT')
             ->samePropertyAs('fullcode', 'tmp')
             ->back('results');
        $this->prepareQuery();

        // substr('a'.$b, 0, 100);
        $this->atomFunctionIs($substrFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
