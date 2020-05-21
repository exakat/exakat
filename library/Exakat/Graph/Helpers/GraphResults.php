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

namespace Exakat\Graph\Helpers;

use stdClass;

class GraphResults implements \ArrayAccess, \Iterator, \Countable {
    const EMPTY   = 0;
    const SCALAR  = 1;
    const ARRAY   = 2;

    private $type = self::EMPTY;
    private $data  = null;

    public function __construct($data = null) {
        // Case of empty result set.

//        print "\nExtracted from JSON\n";
//        var_dump($data);
//        print "\nExtracted from JSON------\n";

// A garder. Aucun résultat.
        if ($data === null) {
            $this->type = self::EMPTY;
            $this->data = $data;

            return;
        }
/*
        if (is_scalar($data)) {
            $this->type = self::SCALAR;
            $this->data = $data;

            return;
        }
*/
// A garder. liste de résultats
        if (is_array($data)) {
            if (!isset($data[0]) || ($data[0] === null)) {
                $this->type = self::EMPTY;
                $this->data = null;

            } else {
                $this->type = self::ARRAY;
                $this->data = $data;
                $this->checkArray();
            }

            return;
        }
/*
        if ($data instanceof stdClass) {
            $this->type = self::ARRAY;
            $this->data = (array) $data;
            $this->checkArray();

            return;
        }
*/
        assert(false, 'Could not understand GraphResults incoming data');
    }

    private function checkArray() {
        if (empty($this->data)) {
            return;
        }
        $data = array_values($this->data);
        if (!($data[0] instanceof stdClass)) {
            return;
        }

        foreach ($this->data as &$data) {
            $data = (array) $data;
        }
        unset($data);
    }

    public function deHash(array $extra = null) {
        if (empty($this->data)) {
            return;
        }

        $result = array();
        foreach($this->data as $value) {
            foreach($value as $k => $v) {
                $result[] = array('', $k, $v);
            }
        }
        if ($extra !== null) {
            $results = array_map(function ($x) use ($extra) { return array_merge($x, $extra); }, $result);
        }

        $this->data = $result;
    }

    public function string2Array(array $extra = null) {
        if (empty($this->data)) {
            return;
        }

        $result = array();
        foreach($this->data as $value) {
            $result[] = array('', array_pop($value));
        }
        if ($extra !== null) {
            $results = array_map($result, function ($x) use ($extra) { return array_merge($x, $extra); });
        }

        $this->data = $result;
    }

    public function toArray(): array {
        if ($this->type === self::EMPTY) {
            return array();
        } else {
            return $this->data;
        }
    }

    public function toString() {
        return (string) $this->data[0];
    }

    public function toInt() {
        if ($this->data === null) {
            return 0;
        }

        return (int) $this->data[0];
    }

    public function isType($type) {
        return $this->type === $type;
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value) {
        // Nothing. No update on that result

    }

    public function offsetUnset($offset) {
        // Nothing. No update on that result

    }

    public function rewind() {
        if ($this->type === self::ARRAY) {
            return reset($this->data);
        }

        return true;
    }

    public function current() {
        return current($this->data);
    }

    public function key() {
        if ($this->type === self::ARRAY) {
            return key($this->data);
        }

        return null;
    }

    public function next() {
        return next($this->data);
    }

    public function valid() {
        if ($this->type === self::ARRAY) {
            return key($this->data) !== null;
        }

        return false;
    }

    public function count() {
        if ($this->type === self::ARRAY) {
            return count($this->data);
        }
        return 0;
    }
}

?>
