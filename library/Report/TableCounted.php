<?php

namespace Report;

class TableCounted {
    private $name = "Unnamed";
    
    function __construct() {
    }
    
    function setQuery($query) {
        $this->queryTemplate = $query; 
    }

    function setContent($name) {
        $this->name = $name; 
    }
    
    
    function toMarkdown() {
        $vertices = query($this->client, $this->queryTemplate)->toArray();
        
        $report = "###{$this->name}\n";
        if (!empty($vertices[0][0])) {
            $report .= "| Item        | Usage          | 
| -------:        | -------:          |\n";
            
            foreach($vertices[0][0] as $k => $v) {
                $k = str_replace( "\n", '<BR />', $k );
                $k = str_replace('|', '\\|', $k);
                $k = str_replace('\\', '\\\\', $k);
                if (strlen($k) > 255) {
                    $k = substr($k, 0, 250).' ...';
                }
                $k = str_replace("\n", '`<br />\n`', $k);
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