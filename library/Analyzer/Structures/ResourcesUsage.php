<?php

namespace Analyzer\Structures;

use Analyzer;

class ResourcesUsage extends Analyzer\Common\Extension {

    public function analyze() {
        // use of Common\Extension, but only really care for functions
        $this->source = 'resource_creation.ini';
        
        parent::analyze();
    }
}

?>