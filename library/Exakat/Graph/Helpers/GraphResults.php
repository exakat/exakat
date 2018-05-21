<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class GraphResults implements \ArrayAccess, \Iterator, \Countable {
    const EMPTY   = 0;
    const SCALAR  = 1;
    const ARRAY   = 2;
    
    private $type = self::EMPTY;
    private $data  = null;
    
    public function __construct($data = null) {
        // Case of empty result set.

        if ($data === null) {
            $this->type = self::EMPTY;
            $this->data = $data;

            return;
        }
        
        if (is_scalar($data)) {
            $this->type = self::SCALAR;
            $this->data = $data;
            
            return;
        }

        if (is_array($data)) {
            $this->type = self::ARRAY;
            $this->data = $data;
            $this->checkArray();
            
            return;
        }

        if ($data instanceof \StdClass) {
            $this->type = self::ARRAY;
            $this->data = (array) $data;
            $this->checkArray();
            
            return;
        }
        
        assert(false, var_dump($data));
    }

    private function checkArray() {
        if (empty($this->data)){
            return;
        }
        $data = array_values($this->data);
        if (!($data[0] instanceof \Stdclass)) {
            return;
        }

        foreach ($this->data as &$data) {
            $data = (array) $data;
        }
        unset($data);
    }
    
    public function toArray() {
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
        return (int) $this->data[0];
    }
    
    public function isType($type) {
        return $this->type === $type;
    }

    public function offsetExists ($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet ($offset) {
        return $this->data[$offset];
    }

    public function offsetSet ($offset, $value) {
        // Nothing. No update on that result
        return;
    }

    public function offsetUnset ( $offset ) {
        // Nothing. No update on that result
        return;
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

    public function count( ) {
        if ($this->type === self::ARRAY) {
            return count($this->data);
        }
        return 0;
    }
}

?>
