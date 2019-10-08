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
    /* PHP version restrictions
    protected $phpVersion = '7.4-';
    */

    /* List dependencies 
    public function dependsOn() {
        return array('Category/Analyzer',
                     '',
                    );
    }
    */
    
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
            return;

            print_r($res->toArray());
            
            $total = 0;
            $query = array();
            foreach($res as $value => $row) {
                $query[] = "('" . $this->sqlite->escapeString($row['name']) . "','" . $this->sqlite->escapeString($row['file']) . "'," . $row['line'] . ')';
                ++$total;
                if ($total % 10000 === 0) {
                    $query = "INSERT INTO literal$type (name, file, line) VALUES " . implode(', ', $query);
                    $this->sqlite->query($query);
                    $query = array();
                }
            }
            
            if (!empty($query)) {
                $query = "INSERT INTO literal$type (name, file, line) VALUES " . implode(', ', $query);
                $this->sqlite->query($query);
            }
            
            $query = "INSERT INTO resultsCounts (analyzer, count) VALUES (\"$type\", $total)";
            $this->sqlite->query($query);
            display( "literal$type : $total\n");
        }

       $otherTypes = array('Null', 'Boolean', 'Closure');
       foreach($otherTypes as $type) {
            $query = <<<GREMLIN
g.V().hasLabel("$type").count();
GREMLIN;
            $total = count($this->gremlin->query($query));

            $query = "INSERT INTO resultsCounts (analyzer, count) VALUES (\"$type\", $total)";
            $this->sqlite->query($query);
            display( "Other $type : $total\n");
       }

       $this->sqlite->query('DROP TABLE IF EXISTS stringEncodings');
       $this->sqlite->query('CREATE TABLE stringEncodings (  
                                              id INTEGER PRIMARY KEY AUTOINCREMENT,
                                              encoding STRING,
                                              block STRING,
                                              CONSTRAINT "encoding" UNIQUE (encoding, block)
                                            )');

        $query = <<<'GREMLIN'
g.V().hasLabel('String').map{ x = ['encoding':it.get().values('encoding')[0]];
    if (it.get().values('block').size() != 0) {
        x['block'] = it.get().values('block')[0];
    }
    x;
}

GREMLIN;
        $res = $this->gremlin->query($query);
        
        $query = array();
        foreach($res as $row) {
            if (isset($row['block'])){
                $query[] = '(\'' . $row['encoding'] . '\', \'' . $row['block'] . '\')';
            } else {
                $query[] = '(\'' . $row['encoding'] . '\', \'\')';
            }
        }
       
       if (!empty($query)) {
           $query = 'REPLACE INTO stringEncodings ("encoding", "block") VALUES ' . implode(', ', $query);
           $this->sqlite->query($query);
       }
    }
}

?>
