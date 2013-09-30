<?php

namespace Analyzer\Common;

class Type extends \Analyzer\Analyzer {
    
    protected $type = null;

    function analyze() {
        $this->atomIs($this->type);
    }
}

?>