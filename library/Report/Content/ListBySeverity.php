<?php

namespace Report\Content;

class ListBySeverity extends \Report\Content\GroupBy {
    
    public function getArray() {
        $array = array();
        
        $severities = array();
        $severities['Critical'] = 3;
        $severities['Major'] = 2;
        $severities['Minor'] = 1;
        $severities['None'] = 0;
        
        foreach($this->analyzers as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->neo4j);
            
            $count = $analyzer->toCount();
            if ($count == 0) { continue; }
            
            $array[] = array('name'     => $analyzer->getName(), 
                             'count'    => $count, 
                             'severity' => $analyzer->getSeverity(),
                             'sort'     => $severities[$analyzer->getSeverity()]);
        }
        
        $this->sort_array($array);
        
        return $array;
    }
}

?>