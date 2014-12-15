<?php

namespace Report\Content;

class Compatibility55 extends \Report\Content {
    protected $array = array();

    public static $deprecatedExtensions = array('apc', 'mysql');
    
    public function collect() {
        $list = \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP55');
        
        foreach($list as $l) {
            $analyzer = \Analyzer\Analyzer::getInstance($l, $this->neo4j);
            $this->array[ $analyzer->getName()] = array('id'     => 1, 
                                                        'result' => $analyzer->toCount());
        }

        $deprecatedExtensions = array_merge( \Report\Content\Compatibility55::$deprecatedExtensions,
                                             \Report\Content\Compatibility54::$deprecatedExtensions,
                                             \Report\Content\Compatibility53::$deprecatedExtensions);
        foreach($deprecatedExtensions as $extension) {
            $analyzer = \Analyzer\Analyzer::getInstance('Analyzer\\Extensions\\Ext'.$extension, $this->neo4j);
            $this->array[ $analyzer->getName()] = array('id'     => 1, 
                                                        'result' => $analyzer->toCount());
        }
        
        return true;
    }
}

?>
