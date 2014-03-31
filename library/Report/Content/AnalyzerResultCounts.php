<?php

namespace Report\Content;

class AnalyzerResultCounts {
    private $analyzers = array();
    private $neo4j = null;
    private $showEmpty = false;
    private $showTotal = true;
    
    public function setAnalyzers($analyzers) {
        $this->analyzers = $analyzers;
    }

    public function setNeo4j($neo4j) {
        $this->neo4j = $neo4j;
    }

    public function showEmpty($show) {
        $this->showEmpty = (bool) $show;
    }
    
    public function toArray() {
        $return = array();
        $total = 0;
        
        foreach($this->analyzers as $analyzer) {
            $o = \Analyzer\Analyzer::getInstance($analyzer, $this->neo4j);
            
            $count = $o->toCount();
            if ($count > 0 || $this->showEmpty) {
                $return[] = array( $o->getName(), $o->toCount(), $o->getSeverity() );
            }
            $total += $count;
        }

        if ($this->showTotal) {
            $return[] = array('Total', $total, '');
        };
        
        return $return;
    }
    
    public function getName() {
        return 'Analyzers result counts';
    }
}

?>