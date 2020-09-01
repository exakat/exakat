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

namespace Exakat\Reports\Helpers;

use SQLite3Result;
use Closure;

class Results {
    private $count        = -1;
    private $values       = null;
    private $options      = array();
    private $res          = null;

    public function __construct(?SQLite3Result $res = null, $options = array()) {
        if ($res === null) {
            $this->values = array();
        } else {
            $this->res = $res;
        }

        $this->options = $options;
        $this->options['phpsyntax'] = $this->options['phpsyntax']  ?? array();
    }

    public function load(): int {
        $this->values = array();
        $this->count  = 0;

        while($row = $this->res->fetchArray(\SQLITE3_ASSOC)) {
            foreach ($this->options['phpsyntax'] as $source => $destination) {
                $row[$destination] = PHPSyntax((string) $row[$source]);
            }
            $this->values[] = $row;
            ++$this->count;
        }

        return $this->count;
    }

    public function isEmpty(): bool {
        if ($this->values === null) {
            $this->load();
        }

        return $this->count === 0;
    }

    public function getCount(): int {
        return $this->count;
    }

    public function getColumn(string $column): array {
        if ($this->values === null) {
            $this->load();
        }

        return array_column($this->values, $column);
    }

    public function toGroupedBy(string $col1, string $col2 = null): array {
        if ($this->values === null) {
            $this->load();
        }

        $return = array();
        if ($col2 === null) {
            foreach($this->values as $row) {
                if (isset($return[$row[$col1]]) ) {
                    $return[$row[$col1]][] = $row;
                } else {
                    $return[$row[$col1]] = array($row);
                }
            }
        } else {
            foreach($this->values as $row) {
                if (!isset($return[$row[$col1]]) ) {
                    $return[$row[$col1]] = array();
                }

                if (!isset($return[$row[$col1]][$col2])) {
                    $return[$row[$col1]][$col2] = array();
                }

                $return[$row[$col1]][$col2][] = $row;
            }
        }

        return $return;
    }

    public function toGroupedCount(string $col = 'name'): array {
        if ($this->values === null) {
            $this->load();
        }

        $return = array();
        foreach($this->values as $row) {
            if (!isset($return[$row[$col]]) ) {
                $return[$row[$col]] = 1;
                continue;
            }

            ++$return[$row[$col]];
        }

        return $return;
    }

    public function toArray(): array {
        if ($this->values === null) {
            $this->load();
        }

        return $this->values;
    }

    public function toArrayHash($key = ''): array {
        if ($this->values === null) {
            $this->load();
        }

        if (empty($key)) {
            return array();
        }

        $return = array();
        foreach($this->values as $value) {
            $return[$value[$key]] = $value;
        }

        return $return;
    }

    public function toList(string $col = null): array {
        if ($this->values === null) {
            $this->load();
        }

        if ($col === null) {
            $col = array_keys($this->values[0])[0];
        }

        return array_column($this->values, $col);
    }

    public function toString(string $col = ''): string {
        if ($this->values === null) {
            $this->load();
        }

        if (empty($this->values)) {
            return '';
        }

        if ($col === '') {
            $first = array_keys($this->values[0])[0];
            return $this->values[0][$first];
        } else {
            return (string) ($this->values[0][$col] ?? '');
        }
    }

    public function toInt(string $col = ''): int {
        if ($this->values === null) {
            $this->load();
        }

        if (empty($this->values)) {
            return 0;
        }

        if ($col === '') {
            $first = array_keys($this->values[0])[0];
            return (int) $this->values[0][$first];
        }

        return (int) ($this->values[0][$col] ?? 0);
    }

    public function toHash(string $key, string $value = ''): array {
        if ($this->values === null) {
            $this->load();
        }

        $return = array();
        if ($value === '') {
            foreach ($this->values as $row) {
                $return[$row[$key]] = $row;
            }
        } else {
            foreach ($this->values as $row) {
                $return[$row[$key]] = $row[$value];
            }
        }

        return $return;
    }

    public function slice(int $begin = 0, int $end = PHP_INT_MAX) {
        if ($this->values === null) {
            $this->load();
        }

        $this->values = array_slice($this->values, $begin, $end);
    }

    public function filter(Closure $f): self {
        if ($this->values === null) {
            $this->load();
        }

        $this->values = array_filter($this->values, $f);

        return $this;
    }

    public function orderBy(string $k): self {
        if ($this->values === null) {
            $this->load();
        }

        $f = function (array $a, array $b) use ($k): int { return $a[$k] <=> $b[$k]; };
        usort($this->values, $f);

        return $this;
    }

    public function order(Closure $f): self {
        if ($this->values === null) {
            $this->load();
        }

        usort($this->values, $f);

        return $this;
    }

    public function map(Closure $f): self {
        if ($this->values === null) {
            $this->load();
        }

        $this->values = array_map($f, $this->values);

        return $this;
    }
}

?>