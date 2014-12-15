<?php

namespace Analyzer\Php;

use Analyzer;

class ShortOpenTagRequired extends Analyzer\Analyzer {

    public function analyze() {
        // This is not actually done here....
        return true;
    }
    
    public function getArray() {
        $r = \Analyzer\Analyzer::$datastore->getRow('shortopentag');
        
        foreach($r as $l) {
            $report[] = array($l['file']);
        }
        
        return $report;
    }
    
    public function hasResults() {
       $r = \Analyzer\Analyzer::$datastore->getRow('shortopentag');
       
       return count($r) > 0;
    }
    
}

?>
