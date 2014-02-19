<?php

namespace Report;

class Sqlite {
    function __construct() { 
        $this->data = array();
    }
    
    function addSummary($add) {    }

    function createH1($name) {  
        $this->current = $name;
    }

    function createH2($name) {  
        $this->current = $name;
    }

    function createH3($name) {  
        $this->current = $name;
    }

    function addContent($type, $data = null) { 
        if (is_null($data)) return ;
        
        if ($type == 'Liste') {
            foreach($data as $d) {
                $this->data[] = array($this->current, $d, '');
            }

            return null;
        } elseif ($type == 'HashTable') {
            foreach($data as $k => $d) {
                $this->data[] = array($this->current, $k, $d);
            }
        } elseif ($type == 'SectionedHashTable') {
            foreach($data as $k => $d) {
                $this->data[] = array($this->current, $k, $d);
            }
        } elseif ($type == 'Text') {
            return null;
        }
        
        return null;
    }

    function toMarkdown() { }
    
    function toText() {    }
    
    function toArray() {
        return $this->data;
    }

}
?>