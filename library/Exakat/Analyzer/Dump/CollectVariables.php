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

class CollectVariables extends AnalyzerTable {
    protected $analyzerName = 'variables';

    protected $analyzerTable = 'variables';

    // Store inclusionss of files within each other
    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE variables (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                          variable STRING,
                          type STRING
                       )
SQL;

    public function analyze(): void {
        $this->atomIs(array('Variable', 'Variablearray', 'Variableobject'), Analyzer::WITHOUT_CONSTANTS)
             ->tokenIs('T_VARIABLE')
             ->savePropertyAs('fullcode', 'variable')
             ->savePropertyAs('label', 'type')
             ->raw(<<<'GREMLIN'
sideEffect{
    variable = variable.replaceAll("&", "").replaceAll("\\.\\.\\.", "").replaceAll("@", "");
    types = ['Variable' : 'var','Variablearray' : 'array', 'Variableobject' : 'object'];
    type = types[type];
}
GREMLIN
)
             ->getVariable(array('variable', 'type'));
        $this->prepareQuery();
    }
}

?>
