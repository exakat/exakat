<?php

namespace Analyzer\Extensions;

use Analyzer;

class Exthash extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'hash.ini';
        
        parent::analyze();
    }
}

?>
