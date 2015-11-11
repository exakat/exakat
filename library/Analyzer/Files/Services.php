<?php

namespace Analyzer\Files;

use Analyzer;

class Services extends Analyzer\Analyzer {
    private $report = null;
    
    public function analyze() {
        // Just a place holder
        return true;
    }

    public function toArray() {
        if ($this->report === null) {
            $this->hasResults();
        }
        
        return $this->report;
    }

    public function hasResults() {
        if ($this->report === null) {
            $this->report = \Analyzer\Analyzer::$datastore->getRow('configFiles');
        }
        
        return count($this->report) > 0;
    }
}

?>
