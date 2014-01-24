<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extssh2 extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'curl.ini';
        
        parent::analyze();
    }
}

?>