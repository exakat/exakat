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


class CollectLiterals extends AnalyzerTable {
    protected $analyzerName = 'Local Variable Counts';

    public function analyze(): void {
        $types = array('Integer', 'Float', 'String', 'Heredoc', 'Arrayliteral');

        foreach($types as $type) {
            $b = microtime(\TIME_AS_NUMBER);
            $this->analyzerTable = "literal$type";
            $this->analyzerSQLTable = <<<SQL
CREATE TABLE literal{$type} (  
                              id INTEGER PRIMARY KEY AUTOINCREMENT,
                              name STRING,
                              file STRING,
                              line INTEGER
                            )
SQL;

            $this->atomIs($type)
                 ->is('constant', true)
                 ->raw(<<<'GREMLIN'
sideEffect{ name = it.get().value("fullcode");
            line = it.get().value('line');
          }
GREMLIN
)
                 ->goToFile()
                 ->savePropertyAs('fullcode', 'file')
                 ->raw(<<<'GREMLIN'
map{ 
  x = ['name': name,
       'file': file,
       'line': line
       ];
}
GREMLIN
);
            $this->prepareQuery();
            $this->execQuery();
        }
/*
       $otherTypes = array('Null', 'Boolean', 'Closure');
       foreach($otherTypes as $type) {
            $query = <<<GREMLIN
g.V().hasLabel("$type").count();
GREMLIN;
            $total = $this->gremlin->query($query)->toInt();

            $query = "INSERT INTO resultsCounts (analyzer, count) VALUES (\"$type\", $total)";
            $this->sqlite->query($query);
            display( "Other $type : $total\n");
       }
*/

            $this->analyzerTable = 'stringEncodings';
            $this->analyzerSQLTable = <<<'SQL'
CREATE TABLE stringEncodings (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                encoding STRING,
                                block STRING,
                                CONSTRAINT "encoding" UNIQUE (encoding, block)
                              )
SQL;

//, 'Concatenation', 'Heredoc' too
        $this->atomIs('String')
             ->raw(<<<'GREMLIN'
map{ 
    x = ['encoding':it.get().values('encoding')[0]];
    if (it.get().values('block').size() != 0) {
        x['block'] = it.get().values('block')[0];
    } else {
        x['block'] = '';
    }
    x;
}
.unique()

GREMLIN
);
        $this->prepareQuery();
    }
}

?>
