<?php

namespace Report;

class TableCounted {
    private $name = "Unnamed";
    private $sort = TableCounted::SORT_NONE;
    
    const SORT_NONE = 1;
    const SORT_COUNT = 2;
    const SORT_REV_COUNT = 3;
    const SORT_KEY = 4;
    const SORT_REV_KEY = 4;
    
    function setQuery($query) {
        $this->queryTemplate = $query; 
    }

    function setContent($name) {
        $this->name = $name; 
    }

    function setSort($sort) {
        if (in_array($sort, range(1, 5))) {
            $this->sort = $sort; 
        }
    }
    
    function toMarkdown() {
        $vertices = query($this->client, $this->queryTemplate)->toArray();
        
        $report = "###{$this->name}\n";
        if (!empty($vertices[0][0])) {
            // report
            $report .= "| Item        | Usage          | 
| -------:        | -------:          |\n";
$report .= "|Number|".count($vertices)."|\n";
$report .= "|Total|".array_sum($vertices)."|\n";
$report .= "\n\n";

            // table
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