<?php

namespace Analyzer\Common;

class Type extends \Analyzer\Analyzer {
    
    protected $type = null;

    public function analyze() {
        $this->atomIs($this->type);
    }
}

?>
