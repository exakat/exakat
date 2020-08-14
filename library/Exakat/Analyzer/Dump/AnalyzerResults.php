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

abstract class AnalyzerResults extends AnalyzerDump {
    protected $storageType = self::QUERY_RESULTS;

    protected $dumpQueries = array();

    public function prepareQuery(): void {
        ++$this->queryId;

        $result = $this->rawQuery();

        ++$this->queryCount;

        $c = $result->toArray();
        if (!is_array($c) || !isset($c[0])) {
            return ;
        }

        $this->processedCount += count($c);
        $this->rowCount       += count($c);

        $valuesSQL = array();
        foreach($c as $row) {
            $row = array_map(array('\\Sqlite3', 'escapeString'), $row);
            $row['analyzer']  = $this->shortAnalyzer;
            $valuesSQL[] = "(NULL, '" . implode("', '", $row) . "', 0) \n";
        }

        $chunks = array_chunk($valuesSQL, SQLITE_CHUNK_SIZE);
        foreach($chunks as $chunk) {
            $query = 'INSERT INTO results VALUES ' . implode(', ', $chunk);
            $this->dumpQueries[] = $query;
        }

        $this->dumpQueries[] = "INSERT INTO resultsCounts (\"id\", \"analyzer\", \"count\") VALUES (NULL, '{$this->shortAnalyzer}', " . (count($valuesSQL)) . ')';

    }

    public function execQuery(): int {
        array_unshift($this->dumpQueries, "DELETE FROM results WHERE analyzer = '{$this->shortAnalyzer}'");

        if (count($this->dumpQueries) >= 2) {
            $this->prepareForDump($this->dumpQueries);
        }

        $this->gremlin->query("g.V({$this->analyzerId}).property(\"count\", " . ($this->rowCount) . ')', array());

        $this->dumpQueries = array();

        return 0;
    }

    public function getDump(): array {
        $dump      = Dump::factory($this->config->dump);

        $res = $dump->fetchAnalysers(array($this->shortAnalyzer));
        return $res->toArray();
    }
}

?>
