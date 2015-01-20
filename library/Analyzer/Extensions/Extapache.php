<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extapache extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'apache.ini';
        
        parent::analyze();
    }
}

?>
