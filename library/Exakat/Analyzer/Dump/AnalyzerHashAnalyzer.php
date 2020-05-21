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
use Exakat\Reports\Helpers\Results;

abstract class AnalyzerHashAnalyzer extends AnalyzerDump {
    protected $storageType = self::QUERY_HASH_ANALYZER;

    protected $dumpQueries = array();

    protected $analyzerValues = array();

    public function prepareQuery(): void {
        $this->processedCount += count($this->analyzerValues);
        $this->rowCount       += count($this->analyzerValues);

        $valuesSQL = array();
        $chunk = 0;
        foreach($this->analyzerValues as $values) {
            $values = array_map(array('\\Sqlite3', 'escapeString'), $values);
            $valuesSQL[] = "('" . join("', '", $values) . "') \n";
        }

        $chunks = array_chunk($valuesSQL, SQLITE_CHUNK_SIZE);
        foreach($chunks as $chunk) {
            $query = 'INSERT INTO hashResults ("name", "key", "value") VALUES ' . implode(', ', $chunk);
            $this->dumpQueries[] = $query;
        }

        $this->prepareForDump($this->dumpQueries);
        $this->dumpQueries = array();
    }

    public function execQuery(): int {
        array_unshift($this->dumpQueries, "DELETE FROM hashAnalyzer WHERE analyzer = '{$this->analyzerName}'");

        if (count($this->dumpQueries) >= 3) {
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

    public function getResults(Dump $dump): Results {
        return $dump->fetchHashResults($this->shortAnalyzer);
    }

}

?>
