<?php

namespace Report;

class HashTable {
    private $hash = array('Empty' => 'hash');
    private $sort = TableCounted::SORT_NONE;
    
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

    function toMarkdown() {
        if (count($this->hash) == 0)  {
            $report = "Nothing special to report. ";
        } else {
            $report = "| Item        | Usage          | 
| -------:        | -------:          |\n";
$report .= "|Total number of element|".array_sum($this->hash)."|\n";
$report .= "|Number of distinct element|".count($this->hash)."|\n";
$report .= "|Largest element|".max($this->hash)." (".array_search(max($this->hash), $this->hash).")|\n";
$report .= "|Smaller element|".min($this->hash)." (".array_search(min($this->hash), $this->hash).")|\n";
$report .= "\n\n";

            $report .= "| Libel        | Value          | 
| -------:        | -------:          |\n";

            foreach($this->hash as $key => $value) {
                $key = $this->escapeString($key);
                $report .= "|$key|$value|\n";
            }
        }
        
        $report .= "\n";
        
        return $report;
    }

    function toText() {
        if (count($this->hash) == 0)  {
            $report = "Nothing special to report. ";
        } else {
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
        }
        
        $report .= "+-------------------------------+\n";
        
        return $report;
    }

    function escapeString($string) {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = str_replace( "\n", '<BR />', $string );
        $string = str_replace('\\', '\\\\', $string);
        $string = str_replace('|', '\\|', $string);
        if (strlen($string) > 255) {
            $string = substr($string, 0, 250).' ...';
        }
        $string = str_replace("\n", '`<br />\n`', $string);
        
        return $string;
    }
}

?>