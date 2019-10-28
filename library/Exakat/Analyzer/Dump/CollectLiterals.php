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

class CollectLiterals extends Analyzer {
    public function analyze() {
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
                 ->raw(<<<GREMLIN
map{ 
  x = ['id': null,
       'name': name,
       'file': file,
       'line': line
       ];
}
GREMLIN
);
        $res = $this->prepareQuery(self::QUERY_TABLE);
    }
}

?>
