<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extapc extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'apc.ini';
        
        parent::analyze();
    }
}

?>
