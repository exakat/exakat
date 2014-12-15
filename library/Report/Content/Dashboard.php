<?php

namespace Report\Content;

class Dashboard extends \Report\Content {
    protected $array = array();
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
