<?php

namespace Analyzer\Php;

use Analyzer;

class Incompilable extends Analyzer\Analyzer {

    public function analyze() {
        // This is not actually done here....
        return true;
    }
    
    public function toArray() {
        $versions = array('53', '54', '55', '56');
        
        $report = array();
        foreach($versions as $version) {
            $r = \Analyzer\Analyzer::$datastore->getRow('compilation'.$version);
            
            foreach($r as $l) {
                $l['version'] = $version;
                $report[] = $l;
            }
        }
        
        return $report;
    }

    public function toFullArray() {
        $report = \Analyzer\Analyzer::$datastore->getRow('compilation53');
        
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
        $versions = array('53', '54', '55', '56');
        
        foreach($versions as $version) {
            $r = \Analyzer\Analyzer::$datastore->getRow('compilation'.$version);
            
            if (count($r) > 0) { return true; }
        }
        return false;
    }
    
}

?>
