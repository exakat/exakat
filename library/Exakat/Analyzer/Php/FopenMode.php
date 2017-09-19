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

class FopenMode extends Analyzer {
    public function analyze() {
        // fopen('path/to/file', 'bbc')
        $this->atomFunctionIs('\\fopen')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('String') // No checks on variable or properties.
             ->hasNoOut('CONCAT')
             ->noDelimiterIsNot(array('r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 't', 't+', 'e', 'e+', // Normal
                                      'rb', 'rb+', 'wb', 'wb+', 'ab', 'ab+', 'xb', 'xb+', 'cb', 'cb+', 'eb', 'eb+',  // binary post
                                      'br', 'br+', 'bw', 'bw+', 'ba', 'ba+', 'bx', 'bx+', 'bc', 'bc+', 'be', 'be+'))  // binary pre
             ->back('first');
        $this->prepareQuery();
    }
}

?>
