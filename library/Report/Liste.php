<?php

namespace Report;

class Liste {
    private $list = array('a', 'b' ,'c' ,'d', 'e');

    function setContent($list = array()) {
        if (!is_null($list)) {
            $this->list = $list; 
        } 
    }
    
    function toMarkdown() {
    }
    
    function toText() {
        if (empty($this->list)) {
            return "Nothing special to report\n\n";
        } else {
            return "\n+ ".join("\n+ ", $this->list)."\n\n";
        }
    }
}

?>