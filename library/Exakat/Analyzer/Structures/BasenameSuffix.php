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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class BasenameSuffix extends Analyzer {
    public function analyze() {
        $substringFunctions = array('\substr', '\mb_substring', '\iconv_substr');

        // substr(basename($path), -4);
        $this->atomFunctionIs($substringFunctions)
             ->outWithRank('ARGUMENT', 0)
             ->functioncallIs('\basename')
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        // basename(substr($path, -4));
        $this->atomFunctionIs('\basename')
             ->noChildWithRank('ARGUMENT', 1)
             ->outWithRank('ARGUMENT', 0)
             ->functioncallIs($substringFunctions)
             ->back('first');
        $this->prepareQuery();
        
        // str_replace('.php', '', basename($path));
        $this->atomFunctionIs(array('\str_replace', '\str_ireplace'))
             ->outWithRank('ARGUMENT', 2)
             ->functioncallIs('\basename')
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        // str_replace('.php', '', basename($path));
        $this->atomFunctionIs('\basename')
             ->noChildWithRank('ARGUMENT', 1)
             ->outWithRank('ARGUMENT', 0)
             ->functioncallIs(array('\str_replace', '\str_ireplace'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
