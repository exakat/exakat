<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Type;

use Exakat\Analyzer\Analyzer;

class MalformedOctal extends Analyzer {
    public $phpVersion = '7.0-';
    
    public function analyze() {
        // malformed Octals
        $this->atomIs('Integer')
             ->regexIs('code', '/^[+-]?0[0-9]+$/')
             ->regexIs('code', '/[89]/');
        $this->prepareQuery();

        // Octals beginning with too many 0
        $this->atomIs('Integer')
             ->regexIs('code', '/^[+-]?0[0-9]+$/')
             ->regexIs('code', '/^[+-]?00+/')
             ->codeIsNot('0000');
        $this->prepareQuery();

        // integer that is defined but will be too big and will be turned into a float
        $maxSize = log(PHP_INT_MAX) / log(2) / 3 + 1;
        $this->atomIs('Real')
             ->regexIs('code', '/^[+-]?0[0-7]{'.$maxSize.',}$/');
        $this->prepareQuery();
    }
}

?>
