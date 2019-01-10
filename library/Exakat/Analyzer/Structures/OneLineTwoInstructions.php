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

class OneLineTwoInstructions extends Analyzer {
    public function analyze() {
        // Two expressions in a row
        $this->atomIs('Sequence')
             ->outIs('EXPRESSION')
             ->atomIsNot('Void')
             ->_as('report')
             ->atomIsNot(array('Ppp', 'Global', 'Const', 'Inlinehtml'))
             ->savePropertyAs('line', 'line_number')
             ->nextSibling()
             ->atomIsNot(array('Inlinehtml', 'Break', 'Continue'))
             ->samePropertyAs('line', 'line_number')
             ->back('report');
        $this->prepareQuery();

        // Two expressions with HTML between
        $this->atomIs('Sequence')
             ->outIs('EXPRESSION')
             ->atomIsNot('Void')
             ->atomIs('Inlinehtml')
             ->nextSibling()
             ->_as('report')
             ->atomIsNot(array('Ppp', 'Global', 'Const', 'Inlinehtml'))
             ->savePropertyAs('line', 'line_number')
             ->nextSibling()
             ->atomIs('Inlinehtml')
             ->nextSibling()
             ->atomIsNot('Inlinehtml')
             ->samePropertyAs('line', 'line_number')
             ->back('report');
        $this->prepareQuery();
    }
}

?>
