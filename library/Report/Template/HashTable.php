<?php

namespace Report\Template;

class HashTable {
    private $hash = array('Empty' => 'hash');
//    private $sort = HashTable::SORT_NONE;
    private $summary = false;

    private $headerName = 'Item';
    private $headerCount = 'Count';
    
    private $countedValues = false;
    
    const SORT_NONE = 1;
    const SORT_COUNT = 2;
    const SORT_REV_COUNT = 3;
    const SORT_KEY = 4;
    const SORT_REV_KEY = 4;
    
    public function render($output) {
        $renderer = $output->getRenderer('HashTable');
        
        if ($this->countedValues) {
            $renderer->render($output, $this->data->toCountedArray());
        } else {
            $renderer->render($output, $this->data->toArray());
        }
    }
    
    function setContent($data) {
        if (!is_null($data)) {
            $this->data = $data; 
        } 
    }

    function setCountedValues($counting = true) {
        $this->countedValues = true;
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
    }

    function toText() {
        $report = "";
        
        foreach($this->hash as $r) {
            $row = "";
            foreach($r as $id => $cell) {
                $row .= " $id : $cell\n";
            }
            $row .= str_repeat('-', 100)."\n";
            
            $report .= $row;
        }
        
        return $report;
    }
}

?>