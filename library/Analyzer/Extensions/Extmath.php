<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmath extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'math.ini';
        
        parent::analyze();
    }
}

?>
