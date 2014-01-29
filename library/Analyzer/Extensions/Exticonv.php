<?php

namespace Analyzer\Extensions;

use Analyzer;

class Exticonv extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'iconv.ini';
        
        parent::analyze();
    }
}

?>