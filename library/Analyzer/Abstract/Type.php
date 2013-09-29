<?php

namespace Analyzer\Abstract;

class Type extends Analyzer {
    
    protected $type = null;

    function analyze() {
        
        $this->atomIs($this->type);
        
        $this->run();
    }
}

?>