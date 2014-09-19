<?php

namespace Analyzer\Classes;

use Analyzer;

class UnresolvedClasses extends Analyzer\Analyzer {
    
    public function analyze() {
        $classes = $this->loadIni('php_classes.ini')['classes'];
        $classes = array_map(function ($class) { return '\\'.strtolower($class); }, $classes);
        
        $this->atomIs("New")
             ->outIs('NEW')
             ->noClassDefinition()
             ->fullnspathIsNot($classes);
        $this->prepareQuery();
        
        // also add property/constant/methods/catch/tryp/typehint
    }
}

?>