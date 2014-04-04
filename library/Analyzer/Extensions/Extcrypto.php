<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extcrypto extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'crypto.ini';
        
        parent::analyze();
    }
}

?>