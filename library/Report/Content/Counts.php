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

class Counts extends \Report\Content {
    public function collect() {
        $config = \Config::factory();
        $datastore = new \Datastore($config);
        
        $themes = array('Analyze', 'Coding Conventions', 'Spip', 'Dead Code', 'Appinfo', 
                        'CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70',
                        );

        $total = 0;
        $analyzes = array();
        foreach($themes as $theme) {
            if (null !== $datastore->getHash($theme)) {
                $analyzers = \Analyzer\Analyzer::getThemeAnalyzers($theme);
                foreach($analyzers as $analyzer) {
                    $analyzer = \Analyzer\Analyzer::getInstance($analyzer, $this->neo4j);
                    if (!$analyzer->isRun()) { 
                        continue; 
                    }

                    $count = $analyzer->getResultsCount();
                    $this->array[] = array($analyzer->getDescription()->getName(), $count);
                    $total += $count;
                }
            }
        } 
    }
}

?>