<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmongo extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'mongo.ini';
        
        parent::analyze();
    }
}

?>