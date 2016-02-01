<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
namespace Analyzer\Php;

use Analyzer;

class FopenMode extends Analyzer\Analyzer {
    public function analyze() {
        // fopen('path/to/file', 'bbc')
        $this->atomFunctionIs('\\fopen')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->hasRank(1)
             ->atomIs('String') // No checks on variable or properties.
             ->hasNoOut('CONTAINS')
             ->noDelimiterIsNot(array('r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 't', 't+',  // Normal
                                      'rb', 'rb+', 'wb', 'wb+', 'ab', 'ab+', 'xb', 'xb+', 'cb', 'cb+',   // binary post
                                      'br', 'br+', 'bw', 'bw+', 'ba', 'ba+', 'bx', 'bx+', 'bc', 'bc+'))  // binary pre 
             ->back('first');
        $this->prepareQuery();
    }
}

?>
