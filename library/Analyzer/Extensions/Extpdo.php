<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extpdo extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'pdo.ini';
        
        parent::analyze();
    }
}

?>