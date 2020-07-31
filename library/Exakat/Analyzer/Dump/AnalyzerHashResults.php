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
use Sqlite3;

abstract class AnalyzerHashResults extends AnalyzerDump {
    protected $storageType = self::QUERY_ARRAYS;

    protected $dumpQueries = array();

    public function prepareQuery(): void {
        ++$this->queryId;

        $result = $this->rawQuery();

        if (count($result) === 0) {
            return ;
        }

        $this->processedCount += count($result->toArray());
        $this->rowCount       += count($result->toArray());

        $valuesSQL = array();
        $chunk = 0;
        foreach($result->toArray() as $row) {
            list($name, $count) = array_values($row);
            $name  = Sqlite3::escapeString((string) $name);
            $count = Sqlite3::escapeString((string) $count);
            $valuesSQL[] = "('{$this->analyzerName}', '$name', '$count') \n";
        }

        $chunks = array_chunk($valuesSQL, SQLITE_CHUNK_SIZE);
        foreach($chunks as $chunk) {
            $query = 'INSERT INTO hashResults ("name", "key", "value") VALUES ' . implode(', ', $chunk);
            $this->dumpQueries[] = $query;
        }
    }

    public function execQuery(): int {
        array_unshift($this->dumpQueries, "DELETE FROM hashResults WHERE name = '{$this->analyzerName}'");

        if (count($this->dumpQueries) >= 1) {
            $this->prepareForDump($this->dumpQueries);
        }

        $this->dumpQueries = array();

        return 0;
    }

    public function getDump(): array {
        $dump      = Dump::factory($this->config->dump);

        $res = $dump->fetchHashResults($this->analyzerName);
        return $res->toArray();
    }
}

?>
