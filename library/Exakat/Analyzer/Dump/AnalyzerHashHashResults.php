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

abstract class AnalyzerHashHashResults extends AnalyzerDump {
    protected $storageType = self::QUERY_HASH;

    protected $dumpQueries = array();

    public function prepareQuery(): void {
        ++$this->queryId;

        $result = $this->rawQuery();

        ++$this->queryCount;

        $c = $result->toArray();
        if (!is_array($c) || !isset($c[0])) {
            return ;
        }
        $c = $c[0];
        if (!is_array($c) || count($c) === 0) {
            return ;
        }

        $this->processedCount += count($c);
        $this->rowCount       += count($c);

        $valuesSQL = array();
        foreach($c as $name => $count) {
            $valuesSQL[] = "('{$this->analyzerName}', '$name', '$count') \n";
        }

        $dumpQueries = array("DELETE FROM hashResults WHERE name = '{$this->analyzerName}'");

        $chunks = array_chunk($valuesSQL, SQLITE_CHUNK_SIZE);
        foreach($chunks as $chunk) {
            $query = 'INSERT INTO hashResults ("name", "key", "value") VALUES ' . implode(', ', $chunk);
            $dumpQueries[] = $query;
        }

        if (count($dumpQueries) >= 2) {
            $this->prepareForDump($dumpQueries);
        }
    }

    public function execQuery(): int {
        array_unshift($this->dumpQueries, "DELETE FROM hashResults WHERE name = '{$this->analyzerName}'");

        if (count($this->dumpQueries) >= 2) {
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
