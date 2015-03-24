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

class AnalyzerResultCounts extends \Report\Content {
    private $analyzers = null;
    
    public function collect() {
        if ($this->analyzers === null) {
            return false;
        }

        $total = 0;
        foreach($this->analyzers as $analyzer) {
            if (is_string($analyzer)) {
                $o = \Analyzer\Analyzer::getInstance($analyzer, $this->neo4j);
            } else if ($analyzer instanceof \Analyzer\Analyzer) {
                $o = $analyzer;
            } else {
                // If we reach here, there is a structural problem.
            }
            
            $count = $o->toCount();
            // only show non-empty
            if ($count == 0) { continue 1; }

            $total += $count;
            $this->array[] = array( $o->getDescription()->getName(), $count, $o->getSeverity() );
        }

        $this->array[] = array('Total', $total, '');
    }
    
    public function setAnalyzers($analyzers) {
        $this->analyzers = $analyzers;
    }
}

?>
