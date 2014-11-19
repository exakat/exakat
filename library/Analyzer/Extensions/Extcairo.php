<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extcairo extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'cairo.ini';
        
        parent::analyze();
    }
}

?>