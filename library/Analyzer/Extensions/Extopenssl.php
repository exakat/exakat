<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extopenssl extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'openssl.ini';
        
        parent::analyze();
    }
}

?>