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


class ConstantOrder extends AnalyzerTable {
    protected $analyzerName = 'constantOrder';

    protected $analyzerTable = 'constantOrder';

    // Store inclusionss of files within each other
    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE constantOrder (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                            built STRING,
                            built_fullcode STRING,
                            building STRING,
                            building_fullcode STRING,
                            CONSTRAINT "unique" UNIQUE (built, building)  ON CONFLICT IGNORE
                        )
SQL;

    public function analyze(): void {
        $this ->atomIs('Constant', self::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->as('built')
              ->as('built_fullcode')
              ->back('first')
              ->outIs('VALUE')
              ->atomIsNot(array('Integer', 'String'))
              ->atomInside(array('Nsname', 'Identifier', 'Staticconstant'))
              ->hasNoIn(array('CLASS'))
              ->as('building')
              ->as('building_fullcode')
              ->select(array('built'             => 'fullnspath',
                             'built_fullcode'    => 'fullcode',
                             'building'          => 'fullnspath',
                             'building_fullcode' => 'fullcode',
                             ));
        $this->prepareQuery();
    }
}

?>