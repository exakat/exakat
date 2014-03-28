<?php

namespace Analyzer\Php;

use Analyzer;

class Incompilable extends Analyzer\Analyzer {

    public function analyze() {
        $this->tokenIs("E_FILE")
             ->is('compile', "'false'");
    }
    
    public function toArray() {
//        $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\\\Php\\\\Incompilable']].out.fullcode"; 
        $queryTemplate = "g.V.has('token', 'E_FILE').has('compile', 'false').fullcode"; 
        $vertices = $this->query($queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices[0] as $k => $v) {
                $report[$k] = $v;
            }   
        } 
        
        return $report;
    }

    public function hasResults() {
        $queryTemplate = "g.V.has('token', 'E_FILE').has('compile', 'false').count()"; 
        $vertices = $this->query($queryTemplate);
        
        return $vertices[0][0] > 0;
    }
    
}

?>