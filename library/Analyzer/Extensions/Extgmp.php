<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extgmp extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'gmp.ini';
        
        parent::analyze();
    }
}

?>