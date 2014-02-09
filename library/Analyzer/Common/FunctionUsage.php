<?php

namespace Analyzer\Common;

use Analyzer;

class FunctionUsage extends Analyzer\Analyzer {
    protected $functions = array();
    
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code($this->functions, false);
    }
}

?>