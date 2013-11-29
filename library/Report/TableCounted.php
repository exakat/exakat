<?php

namespace Report;

class TableCounted {
    private $client = null;
    private $name = "Unnamed";
    
    function __construct($client) {
        $this->client = $client;
    }
    
    function setQuery($query) {
        $this->queryTemplate = $query; 
    }

    function setName($name) {
        $this->name = $name; 
    }
    
    
    function toMarkdown() {
        $vertices = query($this->client, $this->queryTemplate)->toArray();
        
        $report = "###{$this->name}\n";
        if (1) {
            $report .= "| Item        | Usage          | 
| -------:        | -------:          |\n";
            
            foreach($vertices[0][0] as $k => $v) {
                $k = str_replace( "\n", '<BR />', $k );
                $report .= "|`$k`|$v|\n";
            }
        } else {
                $report .= "No {$this->name} used\n";
        }
        $report .= "\n";
        
        return $report;
    }
}

?>