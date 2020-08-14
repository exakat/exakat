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

namespace Exakat\Analyzer\Dump;

class NewOrder extends AnalyzerTable {
    protected $analyzerName = 'newOrder';

    protected $analyzerTable = 'newOrder';

    // Store inclusionss of files within each other
    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE newOrder (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                         calling STRING,
                         called STRING,
                         CONSTRAINT "unique" UNIQUE (calling, called)  ON CONFLICT IGNORE
                        )
SQL;

    public function analyze(): void {
        $this ->atomIs('New', self::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->inIs('DEFINITION')
              ->atomIs('Class')
              ->as('called')
              ->back('first')
              ->goToInstruction('Class')
              ->as('calling')
              ->select(array('calling' => 'fullnspath',
                             'called'  => 'fullnspath'));
        $this->prepareQuery();
    }
}

?>