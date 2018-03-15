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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class MassCreation extends Analyzer {
    public function analyze() {
        // $x[1] = 2; $x['b'] = 2; $x['dc'] = 42; (3 at least)
        $this->atomIs('Array')
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'variable')
             ->back('first')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->nextSibling()
             ->_as('second')
             ->atomIs('Assignation')
             ->codeIs('=', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'variable')
             ->back('second')
             ->nextSibling()
             ->_as('third')
             ->atomIs('Assignation')
             ->codeIs('=', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'variable')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
