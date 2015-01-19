<?php

namespace Analyzer\Common;

use Analyzer;

class FunctionUsage extends Analyzer\Analyzer {
    protected $functions = array();
    
    public function analyze() {
        $functions =  $this->makeFullNsPath($this->functions);
        
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($functions, false);
        $this->prepareQuery();
    }
}

?>
