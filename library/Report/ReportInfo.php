<?php

namespace Report;

class ReportInfo {
    private $list = array();
    
    function __construct($project) {
        $this->setProject($project);
    }

    function setProject($project) {
        $this->list['Audit software version'] = '0.11';
        $this->list['Audit execution date'] = date('r', strtotime('yesterday'));
        $this->list['Report production date'] = date('r', strtotime('last monday'));
        
    }
    
    function toMarkdown() {
    }
    
    function toArray() {
        return $this->list;
    }
}

?>