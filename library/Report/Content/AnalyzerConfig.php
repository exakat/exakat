<?php

namespace Report\Content;

class AnalyzerConfig extends \Report\Content {
    private $analyzer = 'No Analyzer';
    
    public function collect() {
        $config = \Config::factory();
        $analyzer = str_replace('/', '_', $this->analyzer);
        
        $this->list = array_flip($config->$analyzer);
    }

    public function setAnalyzer($analyzer) {
        $this->analyzer = $analyzer;
    }

    public function getArray() {
        $return = array();
        foreach($this->list as $k => $v) {
            $return[] = array($k, $v);
        }
        return $return;
    }

}

?>
