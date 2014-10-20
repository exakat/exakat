<?php

namespace Report\Content;

class ListByFile extends \Report\Content\GroupBy {
    public function toArray() {
        $array = array();
        
        foreach($this->analyzers as $a) {
            $analyzer = \Analyzer\Analyzer::getInstance($a, $this->client);
            
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