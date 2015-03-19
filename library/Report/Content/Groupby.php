<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Report\Content;

class Groupby extends \Report\Content {
    protected $analyzers = array();
    protected $sort = array('Critical', 'Major', 'Minor');

    const SORT_NONE = 0;
    const SORT_VALUES = 1;
    const SORT_RVALUES = 2;
    const SORT_KEYS = 3;
    const SORT_RKEYS = 4;
    const SORT_RANDOM_VALUES = 5;
    const SORT_RANDOM_KEYS = 6;
    const SORT_ARBITRARY = 7;
    
    public function addAnalyzer($analyzer) {
        if (is_array($analyzer)) {
            $this->analyzers = array_merge($this->analyzers, $analyzer);
        } else {
            $this->analyzers[] = $analyzer;
        }
    }

    public function collect() {
        foreach($this->sort as $s) {
            $this->array[$s] = 0;
        }
        
        $m = 'getSeverity';
        $c = 'toCount';
        foreach($this->analyzers as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->neo4j);
            
            $this->array[$analyzer->$m()] += $analyzer->$c();
        }
        
        $this->sort_array($this->array);
        $this->hasResults = (array_sum($this->array) !== 0);

        return true;
    }

    public function getArray() {
        return $this->array;
    }
    
    public function sort_array(&$array) {
        if (is_array($this->sort)) {
            $sort = static::SORT_ARBITRARY;
        } else {
            $sort = (int) $this->sort;
        }

        switch($sort) {
            case static::SORT_VALUES : 
                asort($array);
                break 1;

            case static::SORT_RVALUES : 
                arsort($array);
                break 1;

            case static::SORT_KEYS : 
                ksort($array);
                break 1;

            case static::SORT_RKEYS : 
                krsort($array);
                break 1;

            case static::SORT_RANDOM_VALUES : 
            case static::SORT_RANDOM_KEYS : 
                $this->shuffle_assoc($array);
                break 1;

            case static::SORT_ARBITRARY : 
                $new = array();
                
                foreach($this->sort as $key) {
                    if (isset($array[$key])) {
                        $new[$key] = $array[$key];
                        unset($array[$key]);
                    } 
                }

                foreach($array as $key => $value) {
                    $new[$key] = $value;
                }
                
                $array = $new;
                
                break 1;

            default : // AKA SORT_NONE or all unknown values
        }
        
        return true;
    }

    private function shuffle_assoc(&$array) {
        $keys = array_keys($array);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }
}

?>
