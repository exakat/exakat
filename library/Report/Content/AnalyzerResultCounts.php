<?php

namespace Report\Content;

class AnalyzerResultCounts extends \Report\Content {
    public function collect() {
        $analyzers = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'),
                                 \Analyzer\Analyzer::getThemeAnalyzers('Coding Conventions'));

        $total = 0;
        foreach($analyzers as $analyzer) {
            $o = \Analyzer\Analyzer::getInstance($analyzer, $this->neo4j);
            
            $count = $o->toCount();
            // only show non-empty
            if ($count == 0) { continue 1; }

            $total += $count;
            $this->array[] = array( $o->getDescription()->getName(), $count, $o->getSeverity() );
        }

        $this->array[] = array('Total', $total, '');
    }
}

?>
