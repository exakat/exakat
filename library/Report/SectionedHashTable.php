<?php

namespace Report;

class SectionedHashTable extends Dataset {
    private $hash = array('Empty' => 'hash');
    private $sort = HashTable::SORT_NONE;
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
/*        if ($this->summary && count($this->hash) > 5 ) {
            $values = array(array('Item', ''),
                            array('---', '---'),
                            array('Total number of element', array_sum($this->hash)),
                            array('Number of distinct element', count($this->hash)),
                            );
            $report = $this->toMdTable($values)."\n\n";

//$report .= "|Largest element|".max($this->hash)." (".array_search(max($this->hash), $this->hash).")|\n";
//$report .= "|Smaller element|".min($this->hash)." (".array_search(min($this->hash), $this->hash).")|\n";

         }
         */
         $values = array(array('Section', $this->headerName, $this->headerCount), 
                         array('---','---', '---'));
         foreach($this->hash as $section => $hash) {
            if (empty($hash)) {
                $values[] = array(substr($section, 2), ' ',' ');
            } else {
                $values[] = array(' ', $this->escapeForMarkdown($section), $hash);
            }
/*            foreach($hash as $k => $v) {
            }*/
         }
         $report .= $this->toMdTable($values);
         $report .= "\n";
        
         return $report;
    }

    function toText() {
        if (count($this->hash) == 0)  {
            return "Nothing special to report. ";
        } 
        
        $report = 
"+-----------------------------------------------+
| Section        | Libel        | Value          | 
+-----------------------------------------------+\n";
        foreach($this->hash as $key => $value) {
            if (strlen($key) > 255) {
                $key = substr($key, 0, 250).' ...';
            }
            $report .= "|$key|$value|\n";
        }
        
        $report .= "+-------------------------------+\n";
        
        return $report;
    }
    
    private function toMdTable($array) {
        $r = "";

        $max = array_pad(array(), count($array[0]), 0);
        foreach($array as $row) {
            if (!is_array($row)) { continue; }
            foreach($row as $id => $value) {
                $max[$id] = max($max[$id], strlen($value));
            }
        }
        
        foreach($array as $row) {
            $r .= "| ";
            if (is_array($row)) {
                foreach($row as $id => $value) {
                    if ($value == '---') {
                        $r .= str_repeat('-', $max[$id])." | ";
                    } else {
                        // todo support multiligne
                        $r .= str_repeat(' ', $max[$id] - strlen($value)).$value." | ";
                    }
                }
                $r .= "\n";
            } else {
                $r .= " $row |\n";
            }
        }
        
        return $r;
    }
}

?>