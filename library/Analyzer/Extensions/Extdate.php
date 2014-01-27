<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extdate extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'date.ini';
        
        parent::analyze();
    }
}

?>