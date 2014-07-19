<?php

namespace Analyzer\Structures;

use Analyzer;

class CryptWithoutSalt extends Analyzer\Common\FunctionDefaultValue {
    public $phpversion = "5.6-";
    
    public function analyze() {
        $this->code = 'crypt';
        $this->order = 1;
        
        parent::analyze();
    }
}

?>