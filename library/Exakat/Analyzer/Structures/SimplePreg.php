<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class SimplePreg extends Analyzer {
    public function analyze() {
        // almost data/pcre.ini but not preg_last_error
        $functions = array('\preg_match', '\preg_match_all', '\preg_replace', '\preg_replace_callback',
                           '\preg_filter', '\preg_split', '\preg_quote', '\preg_grep');

        // preg_match('/abc/', $x);
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             // Normal delimiters
             ->regexIsNot('noDelimiter', '(?<!\\\\\\\\)[.?*+\\\\\$\\\\^|{}()\\\\[\\\\]|]')
             // Simple assertions
             ->regexIsNot('noDelimiter', '\\\\\\\\[bBAZzSsDd]')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
