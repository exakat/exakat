<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extdba extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'dba.ini';
        
        parent::analyze();
    }
}

?>
