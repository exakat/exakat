<?php

namespace Report;

class AnalyzerList {
    private $list = array('a', 'b' ,'c' ,'d', 'e');
    
    function __construct($project) {
        $this->setProject($project);
    }

    function setProject($project) {
        // todo add Checks
        $ini = parse_ini_file('./projects/'.$project.'/config.ini');
        
        $this->list = $ini['analyzer'];
    }
    
    function toMarkdown() {
    }
    
    function toArray() {
        return $this->list;
    }
}

?>