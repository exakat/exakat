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


namespace Exakat\Data;

use Exakat\Datastore;

class Dictionary {
    const CASE_SENSITIVE   = true;
    const CASE_INSENSITIVE = false;
    
    private $dictionary = array();
    private $lcindex    = array();
    
    private static $singleton = null;
    
    public function __construct($datastore) {
        $this->dictionary = $datastore->getAllHash('dictionary');
        foreach(array_keys($this->dictionary) as $key) {
            $this->lcindex[mb_strtolower($key)] = 1;
        }
    }
    
    public static function factory($datastore) {
        if (self::$singleton === null) {
            self::$singleton = new self($datastore);
        }
        return self::$singleton;
    }

    public function translate($code, $case = self::CASE_SENSITIVE) {
        $return = array();
        
        $code = makeArray($code);

        if ($case === self::CASE_SENSITIVE) {
            $caseClosure = function ($x) { return $x; };
        } else {
            $caseClosure = function ($x) { return mb_strtolower($x); };
        }

        foreach($code as $c) {
            $d = $caseClosure($c);
            if (isset($this->dictionary[$d])) {
                $return[] = $this->dictionary[$d];
            }
        }
        
        return $return;
    }
    
    public function grep($regex) {
        $keys = preg_grep($regex, array_keys($this->dictionary));
        
        $return = array();
        foreach($keys as $k) {
            $return[] = $this->dictionary[$k];
        }
        
        return $return;
    }

    public function source($code) {
        $return = array();
        
        $reverse = array_flip($this->dictionary);

        foreach($code as $c) {
            if (isset($reverse[$c])) {
                $return[] = $reverse[$c];
            }
        }
        
        return $return;
    }

    public function length($length) {
        $return = array();
        
        if (preg_match('/ > (\d+)/', $length, $r)) {
            $closure = function ($s) use ($r) { return strlen($s) > $r[1]; };
        } elseif (preg_match('/ == (\d+)/', $length, $r)) {
            $closure = function ($s) use ($r) { return strlen($s) === $r[1]; };
        } elseif (preg_match('/ < (\d+)/', $length, $r)) {
            $closure = function ($s) use ($r) { return strlen($s) < $r[1]; };
        } else {
            assert(false, "codeLength didn't understand $length");
        }
        
        $return = array_filter($this->dictionary, $closure, ARRAY_FILTER_USE_KEY);
        
        return array_values($return);
    }

    public function staticMethodStrings() {
        $doublecolon = array_filter($this->dictionary, function ($x) { return strlen($x) > 6 &&
                                                                              strpos($x,' ') === false &&
                                                                              strpos($x,'::') !== false &&
                                                                              mb_strtolower($x) === $x;},
                                                                              ARRAY_FILTER_USE_KEY );
        
        $return = array();
        foreach($doublecolon as $key => $value) {
            // how can this regex fail ?
            if (preg_match('/^[\'"](.+?)::(.+?)/', $key, $r)) {
                $return['\\' . $r[1]] = $value;
            }
        }
        
        return $return;
    }
}
