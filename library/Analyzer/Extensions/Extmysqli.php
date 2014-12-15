<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmysqli extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'mysqli.ini';
        
        parent::analyze();
    }
}

?>
