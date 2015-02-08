<?php

namespace Report\Content;

class Counts extends \Report\Content {
    public function collect() {
        $analyzes = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'),
                                \Analyzer\Analyzer::getThemeAnalyzers('Coding Conventions'));
        
        $analyzes2 = array();
        foreach($analyzes as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->neo4j);
            $analyzes2[$analyzer->getDescription()->getName()] = $analyzer;
        }
        uksort($analyzes2, function($a, $b) { 
            $a = strtolower($a); 
            $b = strtolower($b); 
            if ($a > $b) { 
                return 1; 
            } else { 
                return $a == $b ? 0 : -1; 
            } 
        });

        $total = 0;
        if (count($analyzes) > 0) {
            $this->array = array();
            foreach($analyzes2 as $analyzer) {
                $count = $analyzer->getResultsCount();
                print $analyzer->getDescription()->getName()." : ".$count."\n";
                $this->array[$analyzer->getDescription()->getName()] = $count;
                $total += $count;
            }
        }

    }
}

?>