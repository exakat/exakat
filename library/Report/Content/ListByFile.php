<?php

namespace Report\Content;

class ListByFile extends \Report\Content\GroupBy {
    public function getArray() {
        $array = array();
        
        foreach($this->analyzers as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->neo4j);
            
            $count = $analyzer->toCount();
            if ($count == 0) { continue; }
            
            $files = $analyzer->getFileList();
            foreach($files as $file => $count) {
                if (isset($array[$file])) {
                    $array[$file]['count'] += $count;
                    $array[$file]['sort']  += $count;
                } else {
                    $array[$file] = array('name' => $file,
                                          'count' => $count,
                                          'severity' => '',
                                          'sort' => $count);
                }
            }
        }
        
        $this->sort_array($array);
        
        return $array;
    }
}

?>