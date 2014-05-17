<?php

namespace Analyzer\Php;

use Analyzer;

class Incompilable extends Analyzer\Analyzer {

    public function analyze() {
        $this->tokenIs("E_FILE")
             ->is('compile', "'false'");
    }
    
    public function toArray() {
        $datastore = new \Datastore('chordist');
        $report = $datastore->getRow('compilation53');
        
        return $report;
    }

    public function toFullArray() {
        $datastore = new \Datastore('chordist');
        $report = $datastore->getRow('compilation53');
        
        $return = array();
        if (count($report) > 0) {
            foreach($report as $r) {
                $return[] = array('code' => 'n/a',
                                  'file' => $r['file'],
                                  'line' => $r['line'],
                                  'desc' => $r['error']);
            }   
        } 
        
        return $return;
    }
    
    public function hasResults() {
        $datastore = new \Datastore('chordist');
        $report = $datastore->getRow('compilation53');

        $queryTemplate = "g.V.has('token', 'E_FILE').has('compile', 'false').count()"; 
        $vertices = $this->query($queryTemplate);
        
        return $vertices[0][0] > 0;
    }
    
}

?>