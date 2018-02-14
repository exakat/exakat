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

class Sqlite3RequiresSingleQuotes extends Analyzer {
    public function analyze() {
        // $query = 'select * from table where col = "'.$sqlite->escapeString($x).'"';
        $this->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->atomIs('Methodcall')
             ->savePropertyAs('rank', 'rank')
             ->outIs('METHOD')
             ->codeIs('escapeString')
             ->back('first')
             ->outIs('CONCAT')
             ->isMore('rank', 'rank')
             ->atomIs('String')
             ->regexIs('noDelimiter', '^\\"')
             ->back('first');
        $this->prepareQuery();

        // $x = $sqlite->escapeString($x);
        // $query = 'select * from table where col = "'.$x.'"';
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs('escapeString')
             ->back('first')

             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'variable')
             ->back('first')
             
             ->nextSibling()
             ->atomInside('Concatenation')
             ->outIs('CONCAT')
             ->samePropertyAs('fullcode', 'variable')
             ->savePropertyAs('rank', 'rank')
             ->inIs('CONCAT')
             ->outIs('CONCAT')
             ->isMore('rank', 'rank')
             ->atomIs('String')
             ->regexIs('noDelimiter', '^\\"')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
