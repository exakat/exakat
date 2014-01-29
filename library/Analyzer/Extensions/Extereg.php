<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extereg extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'ereg.ini';
        
        parent::analyze();
    }
}

?>