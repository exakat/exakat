<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extfilter extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'filter.ini';
        
        parent::analyze();
    }
}

?>
