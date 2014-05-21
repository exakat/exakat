<?php

namespace Analyzer\Common;

use Analyzer;

class FunctionDefinition extends Analyzer\Analyzer {
    protected $functions = array();
    
    public function analyze() {
        $this->atomIs("Function")
             ->code($this->functions, false);
    }
}

?>