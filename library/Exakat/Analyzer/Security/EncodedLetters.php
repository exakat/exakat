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

namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class EncodedLetters extends Analyzer {
    public function analyze() {
        // space to z. Include upper/lower case, some classics punctuation.
        // dec : 32 to 122

        // hex : 20 to 7A
        $this->atomIs('String')
             ->hasNoOut('CONCAT')
             ->regexIs('noDelimiter', '\\\\\\\\u\\\\{0*([2-6][0-9a-fA-F]|7[0-9aA])\\\\}')
             ->back('first');
        $this->prepareQuery();

        // unicode codepoint : 20 to 7A
        $this->atomIs('String')
             ->hasNoOut('CONCAT')
             ->regexIs('noDelimiter', '\\\\\\\\x([2-6][0-9a-fA-F]|7[0-9aA])')
             ->back('first');
        $this->prepareQuery();

        // oct : 40 to 172
        $this->atomIs('String')
             ->hasNoOut('CONCAT')
             ->regexIs('noDelimiter', '\\\\\\\\([4-9][0-9]|1[0-6][0-9]|17[0-2])')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
