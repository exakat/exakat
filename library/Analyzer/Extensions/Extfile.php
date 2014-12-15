<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extfile extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'file.ini';
        
        parent::analyze();
    }
}

?>
