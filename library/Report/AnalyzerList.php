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
        
        if (!isset($ini['analyzer'])) {
            $ini = parse_ini_file('./projects/default/config.ini');
        }

        $this->list = $ini['analyzer'];
        
        foreach($this->list as $id => $a) {
            $a = "Analyzer\\".str_replace('/', '\\', $a);
            $analyzer = new $a(null); 
            $this->list[$id] = $analyzer->getName();
        }
    }
    
    function toArray() {
        return $this->list;
    }
}

?>