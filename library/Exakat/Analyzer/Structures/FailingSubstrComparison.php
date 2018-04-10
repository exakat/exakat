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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class FailingSubstrComparison extends Analyzer {
    
    public function analyze() {
        $this->atomIs('Comparison')
             ->codeIs(array('==', '==='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->outIs(array('LEFT', 'RIGHT'))
             ->functioncallIs('\substr')
             ->outWithRank('ARGUMENT', 2)
             ->atomIs('Integer')
             ->isMore('intval', 0)
             ->savePropertyAs('intval', 'length')
             ->back('first')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('String', 'Identifier', 'Nsname'))
             ->hasNoOut('CONCAT')
             
             // Substring is actually as long as length
             ->filter(<<<'GREMLIN'
s = it.get().value("noDelimiter");

// Replace all special chars
s = s.replaceAll(/\\[\\aefnRrt]/, "A");
s = s.replaceAll(/\\0\d\d/, "A");
s = s.replaceAll(/\\u\{[^\}]+\}/, "A");
s = s.replaceAll(/\\[pP]\{^?[A-Z][a-z]?\}/, "A");
s = s.replaceAll(/\\[pP][A-Z]/, "A");
s = s.replaceAll(/\\X[A-Z][a-z]/, "A");
s = s.replaceAll(/\\x[a-fA-F0-9]{2}/, "A");

s.length() != length.toInteger().abs();
GREMLIN
);
        $this->prepareQuery();
    }
}

?>
