<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extdom extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'dom.ini';
        
        parent::analyze();
    }
}

?>
