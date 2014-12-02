<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extparsekit extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'parsekit.ini';
        
        parent::analyze();
    }
}

?>