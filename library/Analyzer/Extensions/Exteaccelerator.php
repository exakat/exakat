<?php

namespace Analyzer\Extensions;

use Analyzer;

class Exteaccelerator extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'eaccelerator.ini';
        
        parent::analyze();
    }
}

?>
