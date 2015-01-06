<?php

namespace Report\Content;

class Compatibility54 extends \Report\Content {
    public static $deprecatedExtensions = array('sqlite');
    
    public function collect() {
        $list = \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP54');
        
        foreach($list as $l) {
            $analyzer = \Analyzer\Analyzer::getInstance($l, $this->neo4j);
            $this->array[ $analyzer->getName()] = array('id'     => 1, 
                                                        'result' => $analyzer->toCount());
        }

        $deprecatedExtensions = array_merge( self::$deprecatedExtensions,
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
