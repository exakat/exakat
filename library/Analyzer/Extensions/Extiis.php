<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extiis extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'iis.ini';
        
        parent::analyze();
    }
}

?>
