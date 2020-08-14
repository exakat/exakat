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
namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class FopenMode extends Analyzer {
    public function analyze(): void {
        // fopen('path/to/file', 'bbc')
        $this->atomFunctionIs('\\fopen')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('String') // No checks on variable or properties.
             ->hasNoOut('CONCAT')
             ->noDelimiterIsNot(array('r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 'e',  // Normal
                                      'rb', 'r+b', 'wb', 'w+b', 'ab', 'a+b', 'xb', 'x+b', 'cb', 'c+b', 'eb',  // binary post
                                      'br', 'br+', 'bw', 'bw+', 'ba', 'ba+', 'bx', 'bx+', 'bc', 'bc+', 'be',  // binary pre
                                      'rt', 'r+t', 'wt', 'w+t', 'at', 'a+t', 'xt', 'x+t', 'ct', 'c+t', 'et',  // text post
                                      'tr', 'tr+', 'tw', 'tw+', 'ta', 'ta+', 'tx', 'tx+', 'tc', 'tc+', 'te',  // text pre
                                      ))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
