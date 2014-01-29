<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extjson extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'json.ini';
        
        parent::analyze();
    }
}

?>