<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
    
    static private $singleton = null;
    
    public function __construct($datastore) {
        $this->dictionary = $datastore->getAllHash('dictionary');
        foreach($this->dictionary as $key => $value) {
            $this->lcindex[mb_strtolower($key)] = 1;
        }
    }
    
    static public function factory($datastore) {
        if (self::$singleton === null) {
            self::$singleton = new self($datastore);
        }
        return self::$singleton;
    }

    public function translate($code, $case = self::CASE_SENSITIVE) {
        $return = array();
        
        $code = makeArray($code);

        if ($case === self::CASE_SENSITIVE) {
            foreach($code as $c) {
                if (isset($this->dictionary[$c])) {
                    $return[] = $this->dictionary[$c];
                }
            }
        } else {
            foreach($code as $c) {
                if (isset($this->dictionary[mb_strtolower($c)])) {
                    $return[] = $this->dictionary[mb_strtolower($c)];
                }
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
            $closure = function ($s) use ($r) { return strlen($s) == $r[1]; };
        } elseif (preg_match('/ < (\d+)/', $length, $r)) {
            $closure = function ($s) use ($r) { return strlen($s) < $r[1]; };
        } else {
            assert(false, "codeLength didn't understand $length");
        }
        
        $return = array_filter($this->dictionary, $closure, ARRAY_FILTER_USE_KEY);
        
        return array_values($return);
    }

    public function closeVariables() {
        $variables = array_filter($this->dictionary, function ($x) { return (strlen($x) > 3) &&
                                                                            (strpos($x, ' ') === false) &&
                                                                            ($x[0] === '$') &&
                                                                            (strpos($x, '.') === false); }, ARRAY_FILTER_USE_KEY );
        
        $return = array();
        foreach($variables as $v1 => $k1) {
            foreach($variables as $v2 => $k2) {
                if ($v1 === $v2) { continue; }
                if ($v1.'s' === $v2) { continue; }
                if ($v1 === $v2.'s') { continue; }
                
                if (levenshtein($v1, $v2) === 1) {
                    $return[$v1] = $k1;
                    $return[$v2] = $k2;
                }
            }
        }
        
        return array_values($return);
    }

    public function caseCloseVariables() {
        $variables = array_filter($this->dictionary, function ($x) { return $x[0] === '$';}, ARRAY_FILTER_USE_KEY );

        $return = array();
        foreach($variables as $v1 => $k1) {
            foreach($variables as $v2 => $k2) {
                if ($v1 === $v2) { continue; }
                
                if (mb_strtolower($v1) === mb_strtolower($v2)) {
                    $return[$v1] = $k1;
                    $return[$v2] = $k2;
                }
            }
        }
        
        return array_values($return);
    }

    public function underscoreCloseVariables() {
        $variables = array_filter($this->dictionary, function ($x) { return (strlen($x) > 3) &&
                                                                            ($x[0] === '$');}, ARRAY_FILTER_USE_KEY );
        
        $return = array();
        foreach($variables as $v1 => $k1) {
            foreach($variables as $v2 => $k2) {
                if ($v1 === $v2) { continue; }
                
                if (str_replace('_', '', $v1) === str_replace('_', '', $v2)) {
                    $return[$v1] = $k1;
                    $return[$v2] = $k2;
                }
            }
        }
        
        return array_values($return);
    }

    public function numberCloseVariables() {
        $variables = array_filter($this->dictionary, function ($x) { return (strlen($x) > 3) &&
                                                                            ($x[0] === '$');}, ARRAY_FILTER_USE_KEY );
        
        $return = array();
        $figures = range(0, 9);
        foreach($variables as $v1 => $k1) {
            foreach($variables as $v2 => $k2) {
                if ($v1 === $v2) { continue; }
                
                if (str_replace($figures, '', $v1) === str_replace($figures, '', $v2)) {
                    $return[$v1] = $k1;
                    $return[$v2] = $k2;
                }
            }
        }
        
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
                $return['\\'.$r[1]] = $value;
            }
        }
        
        return $return;
    }
}
