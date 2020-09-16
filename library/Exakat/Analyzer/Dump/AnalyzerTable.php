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

use Exakat\Dump\Dump;

abstract class AnalyzerTable extends AnalyzerDump {
    protected $storageType = self::QUERY_TABLE;

    protected $dumpQueries = array();

    public function prepareDirectQuery(string $query): void {
        ++$this->queryId;

        $result = $this->gremlin->query($query);

        if (count($result) === 0) {
            return ;
        }

        ++$this->queryCount;

        $c = $result->toArray();
        if (!is_array($c) || !isset($c[0])) {
            return ;
        }

        $this->processedCount += count($c);
        $this->rowCount       += count($c);

        $valuesSQL = array();
        foreach($c as $row) {
            $valuesSQL[] = "(NULL, '" . implode("', '", array_map(array('\\Sqlite3', 'escapeString'), $row)) . "') \n";
        }

        $chunks = array_chunk($valuesSQL, SQLITE_CHUNK_SIZE);
        foreach($chunks as $chunk) {
            $query = 'INSERT INTO ' . $this->analyzerTable . ' VALUES ' . implode(', ', $chunk);
            $this->dumpQueries[] = $query;
        }
    }

    public function prepareQuery(): void {
        ++$this->queryId;

        $result = $this->rawQuery();

        if (count($result) === 0) {
            return ;
        }

        ++$this->queryCount;

        $c = $result->toArray();
        if (!is_array($c) || !isset($c[0])) {
            return ;
        }

        $this->processedCount += count($c);
        $this->rowCount       += count($c);

        $valuesSQL = array();
        foreach($c as $row) {
            $valuesSQL[] = "(NULL, '" . implode("', '", array_map(array('\\Sqlite3', 'escapeString'), $row)) . "') \n";
        }

        $chunks = array_chunk($valuesSQL, SQLITE_CHUNK_SIZE);
        foreach($chunks as $chunk) {
            $query = 'INSERT INTO ' . $this->analyzerTable . ' VALUES ' . implode(', ', $chunk);
            $this->dumpQueries[] = $query;
        }
    }

    public function execQuery(): int {
        assert($this->analyzerTable != 'no analyzer table name', 'No table name for ' . static::class);
        assert($this->analyzerSQLTable != 'no analyzer sql creation', 'No table name for ' . static::class);
        // table always created, may be empty
        if (is_array($this->analyzerSQLTable)) {
            $sql = array_reverse($this->analyzerSQLTable);
            foreach($sql as $query) {
                array_unshift($this->dumpQueries, $query);
            }
        } else {
            array_unshift($this->dumpQueries, $this->analyzerSQLTable);
        }
        array_unshift($this->dumpQueries, "DROP TABLE IF EXISTS {$this->analyzerTable}");

        if (count($this->dumpQueries) >= 3) {
            $this->prepareForDump($this->dumpQueries);
        }

        $this->dumpQueries = array();

        return 0;
    }

    public function getDump(): array {
        $dump      = Dump::factory($this->config->dump);

        $res = $dump->fetchTable($this->analyzerTable);
        return $res->toArray();
    }
}

?>
