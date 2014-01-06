<?php

namespace Report;

class Liste extends Dataset {
    private $list = array('a', 'b' ,'c' ,'d', 'e');

    function setContent($list = array()) {
        if (!is_null($list)) {
            $this->list = $list; 
        } 
    }
    
    function toMarkdown() {
        if (empty($this->list)) {
            return "Nothing special to report\n\n";
        } 
        
        return "\n+ ".join("\n+ ", $this->escapeForMarkdown($this->list))."\n\n";
    }
    
    function toText() {
        if (empty($this->list)) {
            return "Nothing special to report\n\n";
        } 
        
        return "\n+ ".join("\n+ ", $this->list)."\n\n";
    }
}

?>