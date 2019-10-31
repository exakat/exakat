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

namespace Exakat\Analyzer\Dump;

use Exakat\Analyzer\Analyzer;

class TypehintOrder extends Analyzer {
    public function analyze() {
        // Store inclusionss of files within each other
        $this->analyzerTable = "typehintOrder";
        $this->analyzerSQLTable = <<<'SQL'
CREATE TABLE typehintOrder (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                              argument STRING,
                              returned STRING
                        )
SQL;

        $this ->atomIs(self::$FUNCTIONS_ALL, Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RETURNTYPE')
              ->_as('returned')
              ->back('first')
              ->outIs('ARGUMENT')
              ->outIs('TYPEHINT')
              ->atomIsNot(array('Void', 'Scalartypehint'))
              ->_as('argument')
              ->select(array('argument' => 'fullnspath',
                             'returned' => 'fullnspath'));

        $res = $this->prepareQuery(self::QUERY_TABLE);
    }
}

?>
