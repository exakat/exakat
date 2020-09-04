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

use Exakat\Analyzer\Analyzer;

class CollectGlobalVariables extends AnalyzerTable {
    protected $analyzerName = 'globalVariables';

    protected $analyzerTable = 'globalVariables';

    // Store inclusionss of files within each other
    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE globalVariables ( id INTEGER PRIMARY KEY AUTOINCREMENT,
                               variable STRING,
                               file STRING,
                               line INTEGER,
                               isRead INTEGER,
                               isModified INTEGER,
                               type STRING
                             )
SQL;

    public function analyze(): void {
        $this->atomIs('Virtualglobal', Analyzer::WITHOUT_CONSTANTS)
             ->codeIsNot('$GLOBALS', Analyzer::TRANSLATE, Analyzer::CASE_SENSITIVE)
             ->outIs('DEFINITION')
             ->savePropertyAs('label', 'type')
             ->outIsIE('DEFINITION')
             ->_as('variable')
             ->goToInstruction('File')
             ->savePropertyAs('fullcode', 'file')
             ->back('variable')
             ->savePropertyAs('line', 'ligne')
             ->savePropertyAs('fullcode', 'variable')
             ->savePropertyAs('isRead', 'isRead')
             ->savePropertyAs('isModified', 'isModified')
             ->raw(<<<'GREMLIN'
sideEffect{ type = type == "Variabledefinition" ? "implicit" : type == "Globaldefinition" ? "global" : "\$GLOBALS"; }
GREMLIN
)
             ->getVariable(array('file', 'ligne', 'variable', 'isRead', 'isModified', 'type'));
        $this->prepareQuery();
    }
}

?>
