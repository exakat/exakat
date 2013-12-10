<?php

namespace Report;

class Summary {
    private $titles = array();
    
    function __construct() {    }
    
    function toText() {
        
    }
    
    function toMarkdown() {  
        $report = "# Summary\n";
        
        foreach($this->titles as $id => $title) {
            $report .= "+ [$title](#$id)\n";
        }
        
        $report .= "\n";
        
        return $report;
    }
}

?>