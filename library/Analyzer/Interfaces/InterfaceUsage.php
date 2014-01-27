<?php

namespace Analyzer\Interfaces;

use Analyzer;

class InterfaceUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Class")
             ->out('IMPLEMENTS');
    }
}

?>