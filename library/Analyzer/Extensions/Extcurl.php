<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extcurl extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'curl.ini';
        
        parent::analyze();
    }
}

?>
