<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extarray extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'array.ini';
        
        parent::analyze();
    }
}

?>