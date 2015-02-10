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

class ListBySeverity extends \Report\Content\GroupBy {
    
    public function getArray() {
        $array = array();
        
        $severities = array();
        $severities['Critical'] = 3;
        $severities['Major'] = 2;
        $severities['Minor'] = 1;
        $severities['None'] = 0;
        
        foreach($this->analyzers as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->neo4j);
            
            $count = $analyzer->toCount();
            if ($count == 0) { continue; }
            
            $array[] = array('name'     => $analyzer->getDescription()->getName(), 
                             'count'    => $count, 
                             'severity' => $analyzer->getSeverity(),
                             'sort'     => $severities[$analyzer->getSeverity()]);
        }
        
        $this->sort_array($array);
        
        return $array;
    }
}

?>
