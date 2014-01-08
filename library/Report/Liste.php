<?php

namespace Report;

class Liste extends Dataset {
    private $list = array('a', 'b' ,'c' ,'d', 'e');
    private $sort = TableCounted::SORT_NONE;
    private $summary = false;

    const SORT_NONE = 0;
    const SORT_NORMAL = 1;
    const SORT_REVERSE = 2;

    function setContent($list = array()) {
        if (!is_null($list)) {
            $this->list = $list; 
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

    function toMarkdown() {
        if (empty($this->list)) {
            return "Nothing special to report\n\n";
        } 
        
        if ($this->sort) {
            switch( $this->sort) {
                case Liste::SORT_NORMAL : 
                    sort($this->list);
                    break 1;
                case Liste::SORT_REVERSE : 
                    rsort($this->list);
                    break 1;
            }
        }

        $report = '';
        if ($this->summary && count($this->list) > 7 ) {
            $report = "| Item        | Element          | 
| -------:        | -------:          |\n";
$report .= "|Total number of element|".count($this->list)."|\n";
$report .= "|Number of distinct element|".count(array_unique($this->list))."|\n";
$report .= "\n\n";

         }
        
        $report .= "\n+ ".join("\n+ ", $this->escapeForMarkdown($this->list))."\n\n";
        return $report;
    }
    
    function toText() {
        if (empty($this->list)) {
            return "Nothing special to report\n\n";
        } 

        $report = '';
        if ($this->summary) {
            $report = "| {$this->headerName}        | {$this->headerCount}          | 
| -------:        | -------:          |\n";
$report .= "|Total number of element|".array_sum($this->hash)."|\n";
$report .= "|Number of distinct element|".count($this->hash)."|\n";
$report .= "|Largest element|".max($this->hash)." (".array_search(max($this->hash), $this->hash).")|\n";
$report .= "|Smaller element|".min($this->hash)." (".array_search(min($this->hash), $this->hash).")|\n";
$report .= "\n\n";

         }
        
        $report .= "\n+ ".join("\n+ ", $this->list)."\n\n";
        return $report;        
    }
}

?>