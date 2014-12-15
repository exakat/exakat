<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extrunkit extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'runkit.ini';
        
        parent::analyze();
    }
}

?>
