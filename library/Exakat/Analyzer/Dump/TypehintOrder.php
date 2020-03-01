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

use Exakat\Analyzer\Dump\AnalyzerDump;

class Typehintorder extends AnalyzerDump {
    protected $analyzerName = 'typehintOrder';
    
    protected $storageType = self::QUERY_TABLE;
    
    public function analyze() {
        // Store inclusionss of files within each other
        $this->analyzerSQLTable = <<<'SQL'
CREATE TABLE typehintOrder (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                              host STRING,
                              argument STRING,
                              returned STRING
                        )
SQL;

        $this ->atomIs(self::$FUNCTIONS_ALL, Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RETURNTYPE')
              ->as('returned')
              ->atomIsNot(array('Void', 'Scalartypehint'), Analyzer::WITHOUT_CONSTANTS)
              ->back('first')
              ->outIs('ARGUMENT')
              ->outIs('TYPEHINT')
              ->atomIsNot(array('Void', 'Scalartypehint'), Analyzer::WITHOUT_CONSTANTS)
              ->as('argument')
              ->select(array('first'    => 'fullnspath',
                             'argument' => 'fullnspath',
                             'returned' => 'fullnspath'));
        $this->prepareQuery();

        $this ->atomIs(self::$FUNCTIONS_ALL, Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RETURNTYPE')
              ->as('returned')
              ->atomIsNot(array('Void', 'Scalartypehint'), Analyzer::WITHOUT_CONSTANTS)
              ->back('first')
              ->outIs('ARGUMENT')
              ->atomIs('Void')
              ->as('argument')
              ->select(array('first'    => 'fullnspath',
                             'argument' => '\\\\void',
                             'returned' => 'fullnspath'));
        $this->prepareQuery();
    }
}

?>
