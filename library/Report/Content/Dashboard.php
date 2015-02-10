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

class Dashboard extends \Report\Content {
    private $theme = null;

    public function collect() {
        if ($this->theme === null) { return true; }

        $groupBy = new \Report\Content\Groupby($this->neo4j);
        $groupBy->setNeo4j($this->neo4j);
        $groupBy->setDb($this->mysql);
//        $groupBy->setGroupby('getSeverity');
//        $groupBy->setCount('toCount');
//        $groupBy->setSort(array('Critical', 'Major', 'Minor'));
        $groupBy->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers($this->theme) );
        $this->array['upLeft'] = $groupBy;
        
        $infoBox = new \Report\Content\Infobox();
        $infoBox->setNeo4j($this->neo4j);
        $infoBox->setDb($this->mysql);
        $infoBox->setSeverities($groupBy->getArray());
        $infoBox->collect();
        $this->array['upRight'] = $infoBox;

        $listBySeverity = new \Report\Content\ListBySeverity();
        $listBySeverity->setNeo4j($this->neo4j);
        $listBySeverity->setDb($this->mysql);
        $listBySeverity->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers($this->theme));
        $this->array['downLeft'] = $listBySeverity;

        $listByFile = new \Report\Content\ListByFile($this->neo4j);
        $listByFile->setNeo4j($this->neo4j);
        $listByFile->setDb($this->mysql);
        $listByFile->addAnalyzer(\Analyzer\Analyzer::getThemeAnalyzers($this->theme));
        $this->array['downRight'] = $listByFile;
        
        return true;
    }
    
    public function setThema($theme) {
        $this->theme = $theme;
    }
}

?>
