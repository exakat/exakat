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

class ListByFile extends \Report\Content\GroupBy {
    public function getArray() {
        $array = array();
        
        foreach($this->analyzers as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->neo4j);
            
            $count = $analyzer->toCount();
            if ($count == 0) { continue; }
            
            $files = $analyzer->getFileList();
            foreach($files as $file => $count) {
                if (isset($array[$file])) {
                    $array[$file]['count'] += $count;
                    $array[$file]['sort']  += $count;
                } else {
                    $array[$file] = array('name' => $file,
                                          'count' => $count,
                                          'severity' => '',
                                          'sort' => $count);
                }
            }
        }
        
        $this->sort_array($array);
        
        return $array;
    }
}

?>
