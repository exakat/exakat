<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extinfo extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'info.ini';
        
        parent::analyze();
    }
}

?>
