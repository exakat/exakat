<?php

namespace Analyzer\Structures;

use Analyzer;

class McryptcreateivWithoutOption extends Analyzer\Common\FunctionDefaultValue {
    public $phpversion = "5.6-";
    
    public function analyze() {
        $this->code = 'mcrypt_create_iv';
        $this->rank = 1;

        parent::analyze();
    }
}

?>