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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class FetchOneRowFormat extends Analyzer {
    public function analyze() {
        // $res->fetchRow() : Default is slow
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->codeIs('fetchRow')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        // $res->fetchRow(SQLITE3_BOTH) : SQLITE3_BOTH is the worst.
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->codeIs('fetchRow')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('Identifier', 'Nsname'))
             ->fullnspathIs('\\sqlite3_both')
             ->back('first');
        $this->prepareQuery();

        // $res->fetchRow(SQLITE3_NUM), $res->fetchRow(SQLITE3_ASSOC) : OK
    }
}

?>
