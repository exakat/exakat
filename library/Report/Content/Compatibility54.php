<?php

namespace Report\Content;

class Compatibility54 extends \Report\Content {
    private $info = array();

    private $project = null;
    private $neo4j = null;
    private $mysql = null;
    
    public function collect() {
        $list = \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP54');
        
        foreach($list as $l) {
            $analyzer = \Analyzer\Analyzer::getInstance($l, $this->neo4j);
            $this->info[ $analyzer->getName()] = array('id'     => 1, 
                                                       'result' => $analyzer->toCount() ? $analyzer->toCount(). ' warnings' : 'OK');
        }
        
        return true;
    }
    
    public function setNeo4j($client) {
        $this->neo4j = $client;
    }

    public function setMysql($client) {
        $this->mysql = $client;
    }

    public function setProject($project) {
        $this->project = $project;
    }

    public function getInfo() {
        return $this->info;
    }
}

?>