<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extodbc extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'odbc.ini';
        
        parent::analyze();
    }
}

?>