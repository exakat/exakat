<?php

namespace Report;

class HashTable extends Dataset {
    private $hash = array('Empty' => 'hash');
    private $sort = TableCounted::SORT_NONE;
    private $summary = false;

    private $headerName = 'Item';
    private $headerCount = 'Count';
    
    const SORT_NONE = 1;
    const SORT_COUNT = 2;
    const SORT_REV_COUNT = 3;
    const SORT_KEY = 4;
    const SORT_REV_KEY = 4;
    
    function setContent($hash = array()) {
        if (!is_null($hash)) {
            $this->hash = $hash; 
        } 
    }

    function setSort($sort) {
        if (in_array($sort, range(1, 5))) {
            $this->sort = $sort; 
        }
    }

    function setSummary($summary) {
        $this->summary = (bool) $summary;
    }

    function setHeaderName($name) {
        $this->headerName = $name; 
    }

    function setHeaderCount($name) {
        $this->headerCount = $name; 
    }

    function toMarkdown() {
        if (count($this->hash) == 0)  {
            return "Nothing special to report. ";
        } 
        
        $report = "\n\n";
        if ($this->summary && count($this->hash) > 5 ) {
            $report = "|  Item      |  Value         | 
| -------:        | -------:          |\n";
$report .= "|Total number of element|".array_sum($this->hash)."|\n";
$report .= "|Number of distinct element|".count($this->hash)."|\n";
$report .= "|Largest element|".max($this->hash)." (".array_search(max($this->hash), $this->hash).")|\n";
$report .= "|Smaller element|".min($this->hash)." (".array_search(min($this->hash), $this->hash).")|\n";
$report .= "\n\n";

         }

         $report .= "| {$this->headerName}        | {$this->headerCount}          | 
| -------:        | -------:          |\n";
            
            foreach($this->hash as $k => $v) {
                $k = $this->escapeForMarkdown($k);
                $report .= "|$k|$v|\n";
            }
        
        $report .= "\n";
        
        return $report;
    }

    function toText() {
        if (count($this->hash) == 0)  {
            return "Nothing special to report. ";
        } 
        
        $report = 
"+-------------------------------+
| Libel        | Value          | 
+-------------------------------+\n";
        foreach($this->hash as $key => $value) {
            if (strlen($key) > 255) {
                $key = substr($key, 0, 250).' ...';
            }
            $report .= "|$key|$value|\n";
        }
        
        $report .= "+-------------------------------+\n";
        
        return $report;
    }
}

?>