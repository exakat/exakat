<?php

namespace Report;

class Summary {
    private $titles = array();
    
    function __construct() {    }

    function addH1($title, $id = null) {
        if (is_null($id)) {
            $id = str_replace(' ', '_', strtolower($title));
        }
        
        // @todo what if id already exists ? Overwrite ? 
        $this->titles[$id] = $title;

        return $id;
    }

    function addH2($title, $id = null) {
        if (is_null($id)) {
            $id = str_replace(' ', '_', strtolower($title));
        }
        
        // @todo what if id already exists ? Overwrite ? 
        $this->titles[$id] = $title;

        return $id;
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