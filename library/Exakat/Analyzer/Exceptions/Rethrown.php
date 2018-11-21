<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Exceptions;

use Exakat\Analyzer\Analyzer;

class Rethrown extends Analyzer {
    public function analyze() {
        // try {} catch (Exception $e) { throw $e; }
        $this->atomIs('Try')
             ->outIs('CATCH')
             ->outIs('VARIABLE')
             ->savePropertyAs('code', 'rethrow')
             ->inIs('VARIABLE')
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->is('rank', 0)  // Just one expression. Otherwise, some other
             ->atomIs('Throw')
             ->outIs('THROW')
             ->samePropertyAs('code', 'rethrow')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
