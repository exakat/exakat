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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class UseSessionStartOptions extends Analyzer {
    protected $phpVersion = '7.0+';
    
    public function analyze() {
        // ini_set() then session_start
        $this->atomFunctionIs('\\ini_set')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->regexIs('fullcode', '^.session\\\\.')
             ->back('first')
             ->nextSibling()
             ->atomIs('Functioncall')
             ->fullnspathIs('\\session_start')
             ->back('first');
        $this->prepareQuery();

        // session_start then ini_set()
        $this->atomFunctionIs('\\session_start')
             ->nextSibling()
             ->atomIs('Functioncall')
             ->fullnspathIs('\\ini_set')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->regexIs('fullcode', '^.session\\\\.')
             ->regexIsNot('fullcode', '^.session\\\\.name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
