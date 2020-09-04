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

class CollectReadability extends AnalyzerTable {
    protected $analyzerName = 'readability';

    protected $analyzerTable = 'readability';

    // Store inclusionss of files within each other
    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE readability ( id      INTEGER PRIMARY KEY AUTOINCREMENT,
                           name    STRING,
                           type    STRING,
                           tokens  INTEGER,
                           expressions INTEGER,
                           file        STRING
                         )
SQL;

    public function analyze(): void {
        $loops = 20;
        $this->atomIs(array('Function', 'Closure', 'Method', 'Magicmethod', 'File'), Analyzer::WITHOUT_CONSTANTS)
             ->initVariable('functions', '0')
             ->initVariable('name', '""')
             ->initVariable('expression', '0')
             ->not(
                $this->side()
                     ->outIs('BLOCK')
                     ->atomIs('Void')
             )
             ->raw(<<<GREMLIN
     sideEffect{ ++functions; }
    .where(__.coalesce( __.out("NAME").sideEffect{ name=it.get().value("fullcode"); }.in("NAME"),
                        __.identity().sideEffect{ name="global"; file = it.get().value("fullcode");} )
    .sideEffect{ total = 0; expression = 0; type=it.get().label();}
    .coalesce( __.out("BLOCK"), __.out("FILE").out("EXPRESSION").out("EXPRESSION") )
    .repeat( __.out($this->linksDown).not(hasLabel("Class", "Function", "Closure", "Interface", "Trait", "Void")) ).emit().times($loops)
    .sideEffect{ ++total; }
    .not(hasLabel("Void"))
    .where( __.in("EXPRESSION", "CONDITION").sideEffect{ expression++; })
    .where( __.repeat( __.inE().hasLabel($this->linksDown).outV() ).until(hasLabel("File")).sideEffect{ file = it.get().value("fullcode"); })
    .fold()
    )
    .map{
        if (expression > 0) {
            ["name":name, "type":type, "total":total, "expression":expression,  "file":file];
        } else {
            ["name":name, "type":type, "total":total, "expression":0, "file":file];
        }
    }
GREMLIN
);

        // Readability index : "index": 102 - expression - total / expression,
        $this->prepareQuery();
    }
}

?>
