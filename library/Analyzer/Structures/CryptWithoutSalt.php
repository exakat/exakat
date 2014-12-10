<?php

namespace Analyzer\Structures;

use Analyzer;

class CryptWithoutSalt extends Analyzer\Common\FunctionDefaultValue {
    public $phpVersion = "5.6-";
    
    public function analyze() {
        $this->code = 'crypt';
        $this->rank = 1;
        
        parent::analyze();
    }
}

?>